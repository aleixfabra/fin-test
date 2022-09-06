export const uniqueId = () => {
  return parseInt(Date.now().toString().slice(0, 10));
};
