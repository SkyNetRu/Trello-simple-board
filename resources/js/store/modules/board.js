import axios from 'axios'
const state = {
    columns: []
};

const getters = {
    columnsList: state => state.columns
};

const actions = {
    async fetchColumns({commit}){
        const response = await axios.get("/api/columns");
        commit("setColumns", response.data.data)
    },
    async addColumn({commit}, columnTitle){
        const response = await axios.post("/api/column", {title: columnTitle});
        commit("addNewColumn", response.data.data)
    },
    async addCard({commit}, card){
        const response = await axios.post("/api/card", card);
        card = {
            data: response.data.data,
            columnIndex: card.columnIndex
        };
        commit("addNewCard", card)
    },
    async updateColumns ({state}) {
        state.columns.forEach((column, index) => {
            for (let i =0;i < column.cards.length; i++) {
                state.columns[index].cards[i].order = i;
                state.columns[index].cards[i].column_id = column.id
            }
        })

        await axios.post("/api/columns", {columns: state.columns});
    },
    async deleteColumn ({state, commit}, columnIndex) {
        let id = state.columns[columnIndex].id

        await axios.delete("/api/column/" + id);
        commit("removeColumn", columnIndex);
    },

};
const mutations = {
    setColumns: (state, columns) => state.columns = columns,

    addNewColumn: (state, column) => state.columns.push(column),

    addNewCard: (state, card) => state.columns[card.columnIndex].cards.push(card.data),

    removeColumn: (state, columnIndex) => state.columns.splice(columnIndex, 1)
};
export default {
    state,
    getters,
    actions,
    mutations
}
