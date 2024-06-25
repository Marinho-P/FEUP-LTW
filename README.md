# PRE-LOVED

## Group ltw05g03

- Luana Lima (up202206845) 33%
- Pedro Marinho (up202206854) 33%
- Sérgio Nossa (up202206856) 33%


## Install Instructions

(adapt this)

    git clone <git@github.com:FEUP-LTW-2024/ltw-project-2024-ltw05g03.git>
    git checkout final-delivery-v1
    sqlite database/database.db < database/dados.sql
    php -S localhost:9000

## External Libraries

We have used the following external libraries:

- sudo apt-get install php-gd
- crontab -e (use this to eliminate coupons that are expired * * * * * /usr/bin/php ../utils/deleteCouponExpired.php)

## Screenshots

![1.png](Readme/1.png)
![2.png](Readme/2.png)
![3.png](Readme/3.png)

## Implemented Features

**General**:

- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Sellers**  should be able to:

- [x] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [ ] Track and manage their listed items.
- [x] Respond to inquiries from buyers regarding their items and add further information if needed.
- [ ] Print shipping forms for items that have been sold.

**Buyers**  should be able to:

- [x] Browse items using filters like category, price, and condition.
- [x] Engage with sellers to ask questions or negotiate prices.
- [x] Add items to a wishlist or shopping cart.
- [x] Proceed to checkout with their shopping cart (simulate payment process).

**Admins**  should be able to:

- [ ] Elevate a user to admin status.
- [ ] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [ ] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [ ] **SQL injection**
- [ ] **Cross-Site Scripting (XSS)**
- [ ] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: hash_password&verify_password

**Aditional Requirements**:

We also implemented the following additional requirements (you can add more):

- [x] **Rating and Review System**
- [x] **Promotional Features**
- [ ] **Analytics Dashboard**
- [ ] **Multi-Currency Support**
- [ ] **Item Swapping**
- [ ] **API Integration**
- [ ] **Dynamic Promotions**
- [ ] **User Preferences**
- [ ] **Shipping Costs**
- [ ] **Real-Time Messaging System**

