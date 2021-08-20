<template>
  <div class="w-full">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in cart" :key="item.id">
          <td v-text="item.title"></td>
          <td v-text="item.quantity"></td>
          <td v-text="item.price"></td>
          <td>
            <button @click="$store.commit('removeFromCart', index)">
              Remove
            </button>
          </td>
        </tr>
        <tr>
          <td>Total Amount</td>
          <td v-text="cartQuantity"></td>
          <td v-text="cartTotal"></td>
          <td><button @click="purchase()">Buy</button></td>
        </tr>
      </tbody>
    </table>
    <div class="text-center" ref="paypal"></div>
  </div>
</template>
<script>
import baseRequest from "../core/baseRequest";
export default {
  data() {
    return {
      user: [],
      item: [],
      loaded: false,
      paidFor: false,
      product: {
        price: 777.77,
        description: "leg lamp from that one movie",
        img: "./assets/lamp.jpg",
      },
    };
  },
  mounted: function () {
    const script = document.createElement("script");
    script.src =
      "https://www.paypal.com/sdk/js?client-id=ATqr2hBpKcK-1HGCAK9RUT-jeTegvDMsNIgZI5jR7f1p_9gC2nBo5jHePa4P_rBHh3PCipqLk6eTwmzA";
    script.addEventListener("load", this.setLoaded);
    document.body.appendChild(script);
    this.CheckedLogin();
    this.save();
  },
  methods: {
    setLoaded: function () {
      this.loaded = true;
      window.paypal
        .Buttons({
          createOrder: (data, actions) => {
            return actions.order.create({
              purchase_units: [
                {
                  description: "test",
                  amount: {
                    currency_code: "USD",
                    value: this.cartTotal,
                  },
                },
              ],
            });
          },
          onApprove: async (data, actions) => {
            const order = await actions.order.capture();
            this.data;
            this.paidFor = true;
            this.purchase();
            console.log(order);
          },
          onError: (err) => {
            console.log(err);
          },
        })
        .render(this.$refs.paypal);
    },
    CheckedLogin: function () {
      baseRequest
        .get("user")
        .then((response) => {
          this.user = response.data;
          console.log(this.user);
        })
        .catch(() => {
          this.$router.push({ name: "Login" });
        });
    },
    purchase: function () {
      //console.log( this.user.name + " Mua " + this.cartQuantity + " sản phẩm với giá " + this.cartTotal);
      let data = new FormData();
      data.append("user_id", this.user.id);
      data.append("total", this.cartTotal);
      data.append("cart", JSON.stringify(this.cart));
      baseRequest
        .post("order/create", data)
        .then((response) => {
          console.log(response);
          //this.loading = false;
          //this.getPosts();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    save: function () {
      this.cart.forEach((i) => {
        console.log(
          "{name:'" + i.title + "',description :'" + i.content + "',},"
        );
      });
    },
  },
  computed: {
    cart() {
      return this.$store.state.cart;
    },
    cartQuantity() {
      return this.$store.state.cart.reduce((acc, item) => acc + item.quantity, 0);
    },
    cartTotal() {
      let price = this.cart.reduce(
        (acc, item) => acc + item.price * item.quantity,
        0
      );
      //price = price/100;
      return price;
    },
  },
};
</script>
