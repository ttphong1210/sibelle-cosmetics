import eventBus from "@/utils/eventBus";
import { FavoriteProductService } from "@/utils/favorite";

export default {
  data() {
    return {
      favorites: FavoriteProductService.getFavorite(),
    };
  },
  created() {
    eventBus.on("load-favorite", (updatedFavorites) => {
      this.favorites = updatedFavorites;
    });
  },
  beforeUnmount() {
    eventBus.off("load-favorite");
  },
  methods: {
    findItemFavorite(product) {
      return this.favorites.some((item) => item.prod_id === product.prod_id);
    },
    clickToggleFavorite(product) {
      this.favorites = FavoriteProductService.addFavoriteProduct(product);
    },
  },
};
