import LoginCustomerComponent from "../components/pages/accountCustomer/LoginCustomerComponent.vue";

const account_customer = [
    {
        path: '/accounts',
        children:[
            {
                path: 'login-customer.html',
                component: LoginCustomerComponent,
            }
        ]
    }
];

export default account_customer;