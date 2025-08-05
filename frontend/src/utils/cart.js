import eventBus from "./eventBus";
import { saleApi } from "@/api/sale";

class CartService {
  static getCart() {
    const saveCartData = localStorage.getItem("cart");
    return saveCartData ? JSON.parse(saveCartData) : [];
  }

  static saveCartToLocalStorage(cart) {
    localStorage.setItem("cart", JSON.stringify(cart));
  }
  static async removeFromCart(productId) {
    try {
      let cart = this.getCart();
      const item = cart.find((product) => product.id === productId);
      if (item) {
        await saleApi.deleteReserveStock([
          {
            product_id: productId,
            quantity: item.quantity,
          },
        ]);
      }
      cart = cart.filter((item) => item.id !== productId);
      this.saveCartToLocalStorage(cart);
      eventBus.emit("load-cart");
    } catch (error) {
      console.log("Error delete item cart:", error);
      throw new Error("Không thể xoá sản phẩm khỏi giỏ hàng");
    }
  }
  //kiểm tra tồn kho trước khi thêm vào giỏ hàng
  static async checkInventory(productId, quantity) {
    try {
      const response = await saleApi.checkInventory([
        {
          product_id: productId,
          quantity: quantity,
        },
      ]);

      return response.data.data[0];
    } catch (error) {
      console.error("Error checking inventory:", error);
      throw new Error("Không thể kiểm tra tồn kho");
    }
  }
  static async addToCart(product, requestedQuantity = 1) {
    try {
      let cart = this.getCart();
      const cartItemIndex = cart.findIndex(
        (item) => item.id === product.prod_id
      );
      const currentQuantity =
        cartItemIndex !== -1 ? cart[cartItemIndex].quantity : 0;
      const totalQuantity = currentQuantity + requestedQuantity;
      const stockCheck = await this.checkInventory(
        product.prod_id,
        totalQuantity
      );

      if (!stockCheck.available) {
        alert(`Không thể thêm sản phẩm: ${stockCheck.message}`);
        return { success: false, message: stockCheck.message };
      }
      const reservedStock = await saleApi.reservedStock({
        product_id: product.prod_id,
        quantity: totalQuantity,
      });
      if (!reservedStock.data.success) {
        return { success: false, message: reservedStock.data.message };
      }
      cart = this.getCart();
      if (cartItemIndex !== -1) {
        cart[cartItemIndex].quantity += requestedQuantity;
      } else {
        cart.push({
          id: product.prod_id,
          name: product.prod_name,
          slug: product.prod_slug,
          price: product.prod_price,
          img_url: product.prod_img,
          quantity: requestedQuantity,
        });
      }
      this.saveCartToLocalStorage(cart); // Lưu lại giỏ hàng
      alert("Thêm sản phẩm vào giỏ hàng thành công !");
      eventBus.emit("load-cart");

      return {
        success: true,
        availableQuantity: stockCheck.available_quantity,
      };
    } catch (error) {
      alert("Lỗi khi thêm vào giỏ hàng, vui lòng thử lại!");
      console.error("Add to cart:", error);
      return {
        success: false,
        message: error.message,
      };
    }
  }

  static async updateCart(id, quantity) {
    if (quantity < 1) return;
    try {
      const stockCheck = await this.checkInventory(id, quantity);
      if (!stockCheck.available) {
        alert(`Không thể cập nhật: ${stockCheck.message}`);
        return { success: false, message: stockCheck.message };
      }

      let cart = this.getCart();
      const cartItem = cart.findIndex((item) => item.id === id);
      if (cart[cartItem].quantity < quantity) {
        const reservedStock = await saleApi.reservedStock({
          product_id: id,
          quantity: 1,
        });
        if (!reservedStock.data.success) {
          alert(`Không thể cập nhật: ${reservedStock.data.message}`);
          return { success: false, message: reservedStock.data.message };
        }
      } else if (cart[cartItem].quantity > quantity) {
        const reservedStock = await saleApi.deleteReserveStock([
          {
            product_id: id,
            quantity: 1,
          },
        ]);
        if (!reservedStock.data.success) {
          alert(`Không thể cập nhật: ${reservedStock.data.message}`);
          return { success: false, message: reservedStock.data.message };
        }
      }
      cart = this.getCart();

      if (cartItem !== -1) {
        cart[cartItem].quantity = quantity;
        this.saveCartToLocalStorage(cart);
        eventBus.emit("load-cart");
        return { success: true };
      }
    } catch (error) {
      console.log("Error update cart:", error);
    }
  }
  static clearCart() {
    localStorage.removeItem("cart");
    eventBus.emit("load-cart");
  }
}
export { CartService };
