<template>
    <div class="wraper">
        <div class="column"
             v-for="(column, index) in columnsList"
             :key="index"
        >
            <div class="column__header">
                <div class="column__header__name">{{ column.title }}</div>
                <div class="column__header__delete_icon"
                     @click="showDeleteColumnModal(index)">
                    <img src="/svg/trash-solid.svg">
                </div>
            </div>
            <div class="column__add_card_btn my-2">
                <b-button class="float-end"
                          variant="success"
                          @click="showAddCardModal(column.id, index)">
                    Add Card
                </b-button>
            </div>

            <draggable
                v-if="column.cards && column.cards.length"
                :list="columnsList[index].cards"
                class="list-group"
                draggable=".item"
                group="a"
                @end="onEnd"
            >
                <div class="column__card item"
                     v-for="(card, cIndex) in column.cards"
                     :key="cIndex"
                     @click="showEditCardModal(card)"
                >
                    <div class="column__card__title">{{ card.title }}</div>
                </div>
            </draggable>
        </div>

        <div class="add-column">
            <b-button variant="info" v-b-modal.add-column-modal>Add Column</b-button>
        </div>

        <b-modal id="add-column-modal" title="Add Column"
                 @ok="addColumn(columnTitle)"
                 @show="columnTitle = ''"
                 ok-title="Add column"
        >
            <b-form-group
                label="Title:"
                label-for="column-title"
            >
                <b-form-input
                    id="column-title"
                    v-model="columnTitle"
                    type="text"
                    placeholder="Enter title"
                    required
                ></b-form-input>
            </b-form-group>
        </b-modal>

        <b-modal id="card-modal"
                 ref="card-modal"
                 :title="cardModalTitle"
                 @ok="storeCard"
                 :ok-title="cardOkTitle"
        >
            <b-form-group
                label="Title:"
                label-for="card-title"
            >
                <b-form-input
                    id="card-title"
                    v-model="cardTitle"
                    type="text"
                    placeholder="Enter title"
                    required
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Description:"
                label-for="card-desc"
            >
                <b-form-input
                    id="card-desc"
                    v-model="cardDesc"
                    type="text"
                    placeholder="Enter description"
                    required
                ></b-form-input>
            </b-form-group>
        </b-modal>
    </div>

</template>

<script>
import draggable from "@/vuedraggable";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "Trello",
    components: {
        draggable
    },
    data() {
        return {
            columnTitle: '',
            columnId: null,
            columnIndex: null,
            cardTitle: '',
            cardDesc: '',
            isEditCard: false
        };
    },
    methods: {
        ...mapActions(["fetchColumns", "addColumn", "addCard", "updateColumns", "deleteColumn"]),
        showAddCardModal(id, index) {
            this.isEditCard = false;
            this.columnId = id;
            this.columnIndex = index;
            this.cardTitle = '';
            this.cardDesc = '';
            this.$refs['card-modal'].show()
        },
        showEditCardModal (card) {
            this.isEditCard = true;
            this.cardTitle = card.title;
            this.cardDesc = card.desc;
            this.$refs['card-modal'].show();
        },
        storeCard() {
            let params = {
                columnIndex: this.columnIndex,
                column_id: this.columnId,
                title: this.cardTitle,
                desc: this.cardDesc
            };

            this.addCard(params);
        },
        onEnd() {
            this.updateColumns();
        },
        showDeleteColumnModal(columnIndex) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete the column.', {
                title: 'Deleting column',
                okVariant: 'danger',
                okTitle: 'Delete',
                cancelTitle: 'Cancel',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.deleteColumn(columnIndex);
                    }
                })
        }
    },
    computed: {
        ...mapGetters(["columnsList"]),
        cardOkTitle: () => {
            return this.isEditCard ? 'Update card' : 'Add card';
        },
        cardModalTitle: () => {
            return this.isEditCard ? 'Edit card' : 'Add card' ;
        },
    },
    mounted() {
        this.fetchColumns();
    }
}
</script>

<style lang="scss" scoped>
.wraper {
    display: flex;
    flex: 1 1 auto;
}

.column {
    display: flex;
    flex-direction: column;
    align-self: auto;
    flex: 1 1 auto;
    padding: 0 12px;
    margin-left: 6px;
    margin-right: 6px;
    min-width: 270px;
    max-width: 270px;
    box-shadow: initial;
    background-color: #F4F5F7;

    &__header {
        height: 48px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        align-content: space-between;
        padding: 6px 15px;
        border-bottom: 1px solid;

        &__delete_icon {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    }

    &__card {
        margin: 6px 0px;
        background: azure;
        cursor: pointer;
    }
}
</style>
