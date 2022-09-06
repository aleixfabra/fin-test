<template>
  <div class="allocations">
    <h2>Allocations</h2>
    <SpinnerLoader v-if="!store.portfolio.allocations" />
    <table v-if="store.portfolio.allocations">
      <tr>
        <th>Allocation</th>
        <th>Shares</th>
      </tr>
      <tr
        v-for="allocation in store.portfolio.allocations"
        :key="allocation.id"
      >
        <td>{{ allocation.id }}</td>
        <td>{{ allocation.shares }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
import { store } from "@/store/store.js";
import SpinnerLoader from "@/components/SpinnerLoader";

export default {
  name: "AllocationsList",
  components: { SpinnerLoader },
  data() {
    return {
      store,
    };
  },
};
</script>
