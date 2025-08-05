import { CartService } from "@/utils/cart"

export default {
    methods:{
        actionAddToCart(product){
            CartService.addToCart(product);
        },
    },
};