export default {
    methods: {
      formatCurrency(value) {
        return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(value);
      },

      getDetailProductUrl(id, slug) {
        return `/detail/${id}/${slug}.html`;
      },

      getProductAvatar(image){
        return `http://192.168.2.2:8080/storage/avatar/${image}`;
      }

    },
  };
  