<template>
  <div class="portfolio">
    <h1>Portfolio #{{ store.portfolio.id }}</h1>
    <AllocationsList />
    <OrdersList />
  </div>
</template>

<script>
// @ is an alias to /src
import AllocationsList from "@/components/AllocationsList.vue";
import OrdersList from "@/components/OrdersList.vue";
import { store } from "@/store/store";

export default {
  name: "PortfolioView",
  components: {
    AllocationsList,
    OrdersList,
  },
  data() {
    return {
      store,
    };
  },
};
</script>
