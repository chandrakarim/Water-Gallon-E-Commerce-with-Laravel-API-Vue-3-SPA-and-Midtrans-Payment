import { defineStore } from "pinia"
import axios from "@/api/axios"

export const useCartStore = defineStore("cart",{

state:()=>({

items: [],
totalQty:0,
totalPrice:0

}),

actions:{

async getCart(){

const res = await axios.get("/cart")

const cart = res.data.data

this.items = cart.items || []
this.totalQty = cart.total_qty
this.totalPrice = cart.total_price

},

async addCart(product_id, qty = 1){

await axios.post("/cart/add",{
product_id,
qty
})

await this.getCart()

},

async updateQty(id, qty){

await axios.patch(`/cart/${id}`,{
qty
})

await this.getCart()

},

async deleteCart(id){

await axios.delete(`/cart/${id}`)

await this.getCart()

},

async clearCart(){

await axios.delete("/cart")

this.items = []
this.totalQty = 0
this.totalPrice = 0

}

}

})