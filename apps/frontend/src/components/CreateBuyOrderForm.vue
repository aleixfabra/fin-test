<template>
  <div class="create-buy-order-form">
    <form @submit.prevent="handleSubmit">
      <h4>Create Buy Order</h4>
      <div class="form-group">
        <label>Allocation</label>
        <input
          v-model="allocation"
          type="number"
          class="form-control"
          required
        />
      </div>
      <div class="form-group">
        <label>Shares number</label>
        <input v-model="shares" type="number" class="form-control" required />
      </div>
      <button type="submit" class="primary">Create</button>
    </form>
  </div>
</template>

<script>
import { store } from "@/store/store.js";

export default {
  name: "CreateBuyOrderForm",
  data: function () {
    return {
      allocation: null,
      shares: null,
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("order-created");

      store.createBuyOrder(this.allocation, this.shares);

      this.clearForm();
    },
    clearForm() {
      this.allocation = this.shares = null;
    },
  },
};
</script>
