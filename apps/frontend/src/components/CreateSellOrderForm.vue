<template>
  <div class="create-sell-order-form">
    <form @submit.prevent="handleSubmit">
      <h4>Create Sell Order</h4>
      <div class="form-group">
        <label>Allocation</label>
        <select v-model="allocation" required>
          <option
            v-for="allocation in store.portfolio.allocations"
            :value="allocation.id"
            :key="allocation.id"
          >
            {{ allocation.id }}
          </option>
        </select>
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
      store,
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("order-created");

      store.createSellOrder(this.allocation);

      this.clearForm();
    },
    clearForm() {
      this.allocation = null;
    },
  },
};
</script>
