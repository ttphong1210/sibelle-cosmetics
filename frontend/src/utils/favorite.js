import eventBus from "./eventBus";

class FavoriteProductService{

    static getFavorite(){
        const storedFavorite = localStorage.getItem("favorite");
        return storedFavorite ? JSON.parse(storedFavorite) : [];
    }

    static saveFavoriteToLocalStorage(favorite){
        localStorage.setItem('favorite', JSON.stringify(favorite));
    }

    static findItemFavorite(product){
        let favorite = this.getFavorite();
        return favorite.findIndex(item => item.prod_id === product.prod_id);
    }
    static addFavoriteProduct(product){
        try{
            let favorite = this.getFavorite();
            const favoriteItemIndex = this.findItemFavorite(product);
            if(favoriteItemIndex !== -1){
                favorite.splice(favoriteItemIndex, 1);
                alert('Đã xóa sản phẩm khỏi yêu thích!');
            }else{
                favorite.push({
                    prod_id: product.prod_id,
                    prod_name: product.prod_name,
                    prod_slug: product.prod_slug,
                    prod_price: product.prod_price,
                    prod_img: product.prod_img,
                });
                alert('Đã thêm sản phẩm vào yêu thích!');
            }
            this.saveFavoriteToLocalStorage(favorite);
            eventBus.emit("load-favorite", favorite);
            return favorite;
        }catch(error){
            alert('Lỗi thêm sản phẩm vào yêu thích!', error);
            return this.getFavorite();
        }
    }
}

export {FavoriteProductService};