import ApiService from "@/services/ApiService";
import { reactive } from "vue";
import { uniqueId } from "@/helpers";

const DEFAULT_PORTFOLIO_ID = 1;

export const store = reactive({
  portfolio: {
    id: DEFAULT_PORTFOLIO_ID,
    allocations: null,
    orders: null,
  },
  async fetchPortfolioData() {
    this.portfolio.allocations = await ApiService.getPortfolioAllocations(
      this.portfolio.id
    );
    this.portfolio.orders = await ApiService.getPortfolioOrders(
      this.portfolio.id
    );
  },
  createBuyOrder(allocationId, shares) {
    this._createOrder({
      allocation: allocationId,
      shares: shares,
      type: "buy",
    });
  },
  createSellOrder(allocationId) {
    const allocation = this._findAllocationById(allocationId);

    this._createOrder({
      allocation: allocation.id,
      shares: allocation.shares,
      type: "sell",
    });
  },
  completeOrder(order) {
    ApiService.completeOrder(order.id);

    order.completed = true;
    this._executeOrder(order);
  },
  _addAllocation(allocationData) {
    this.portfolio.allocations[allocationData.id] = allocationData;
  },
  _removeAllocation(id) {
    delete this.portfolio.allocations[id];
  },
  _addOrder(orderData) {
    this.portfolio.orders[orderData.id] = orderData;
  },
  _createOrder(orderData) {
    orderData.id = uniqueId();
    orderData.portfolio = this.portfolio.id;

    ApiService.createOrder(orderData);

    this._addOrder(orderData);
  },
  _executeOrder(order) {
    if (order.type === "buy") {
      this._completeBuyOrder(order);
    } else if (order.type === "sell") {
      this._completeSellOrder(order);
    }
  },
  _completeBuyOrder(order) {
    const allocation = this._findAllocationById(order.allocation);

    if (allocation) {
      allocation.shares += order.shares;
    } else {
      this._addAllocation({
        id: order.allocation,
        shares: order.shares,
      });
    }
  },
  _completeSellOrder(order) {
    const allocation = this._findAllocationById(order.allocation);

    if (allocation.shares > order.shares) {
      allocation.shares -= order.shares;
    } else {
      this._removeAllocation(allocation.id);
    }
  },
  _findAllocationById(id) {
    return this.portfolio.allocations[id];
  },
});
