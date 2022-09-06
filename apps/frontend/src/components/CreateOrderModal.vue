<template>
  <div class="create-order-modal">
    <button class="primary" @click="openModal = true">+ New Order</button>
    <div v-if="openModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <div class="options center">
            <button
              class="outline-primary"
              :class="{ active: !isBuyOrder }"
              @click="isBuyOrder = false"
            >
              Sell
            </button>
            <button
              class="outline-primary"
              :class="{ active: isBuyOrder }"
              @click="isBuyOrder = true"
            >
              Buy
            </button>
          </div>
          <button class="close-btn outline-primary" @click="openModal = false">
            x
          </button>
        </div>
        <div class="modal-body">
          <CreateBuyOrderForm
            v-if="isBuyOrder"
            @order-created="orderCreated($event)"
          />
          <CreateSellOrderForm
            v-if="!isBuyOrder"
            @order-created="orderCreated($event)"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CreateBuyOrderForm from "@/components/CreateBuyOrderForm";
import CreateSellOrderForm from "@/components/CreateSellOrderForm";

export default {
  name: "CreateOrderModal",
  components: {
    CreateBuyOrderForm,
    CreateSellOrderForm,
  },
  data() {
    return {
      openModal: false,
      isBuyOrder: true,
    };
  },
  methods: {
    orderCreated() {
      this.openModal = false;
    },
  },
};
</script>

<style scoped lang="scss">
.options {
  button {
    margin: 0 5px;
  }
}
</style>
