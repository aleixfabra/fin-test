import axios from "axios";

class ApiService {
  getPortfolioAllocations(id) {
    return axios
      .get(`api/portfolios/${id}`)
      .then(({ data }) => data)
      .then(({ allocations }) => allocations)
      .then((allocations) =>
        allocations.reduce((allocations, allocation) => {
          const { id } = allocation;
          allocations[id] = allocation;
          return allocations;
        }, {})
      );
  }

  getPortfolioOrders(id) {
    return axios
      .get(`api/portfolios/${id}/orders`)
      .then(({ data }) => data)
      .then(({ orders }) => orders)
      .then((orders) =>
        orders.reduce((orders, order) => {
          const { id } = order;
          orders[id] = order;
          return orders;
        }, {})
      );
  }

  createOrder(data) {
    axios.post("api/orders", data);
  }

  completeOrder(id) {
    axios.patch(`api/orders/${id}`, {
      status: "completed",
    });
  }
}

export default new ApiService();
