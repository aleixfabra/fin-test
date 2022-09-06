<template>
  <div class="orders">
    <h2>Orders</h2>
    <CreateOrderModal />
    <SpinnerLoader v-if="!store.portfolio.orders" />
    <table v-if="store.portfolio.orders">
      <tr>
        <th>Id</th>
        <th>Allocation</th>
        <th>Shares</th>
        <th>Type</th>
        <th></th>
      </tr>
      <tr v-for="order in notCompleted(store.portfolio.orders)" :key="order.id">
        <td>{{ order.id }}</td>
        <td>{{ order.allocation }}</td>
        <td>{{ order.shares }}</td>
        <td>{{ order.type }}</td>
        <td>
          <button
            class="outline-primary"
            v-on:click="store.completeOrder(order)"
          >
            Complete
          </button>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { store } from "@/store/store.js";
import CreateOrderModal from "@/components/CreateOrderModal";
import SpinnerLoader from "@/components/SpinnerLoader";

export default {
  name: "OrdersList",
  components: { SpinnerLoader, CreateOrderModal },
  data() {
    return {
      store,
    };
  },
  methods: {
    notCompleted(orders) {
      const ordersArray = Object.entries(orders);
      const nonCompletedOrders = ordersArray.filter(
        ([, order]) => !order.completed
      );
      return Object.fromEntries(nonCompletedOrders);
    },
  },
};
</script>
