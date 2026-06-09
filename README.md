# AutoParts Pro - Management System

This is a sales and inventory management system built specifically for autoparts shops in Tanzania. It helps you keep track of your inventory, process sales quickly, and manage transactions easily. Everything is in Tanzanian Shillings (TZS) so no need to worry about currency conversion.

## Screenshots

**Dashboard**
![Dashboard Screenshot](images/dashboard.png)
See your business stats at a glance - total products, revenue, recent sales, and low stock alerts.

**Products Page**
![Products Screenshot](images/products.png)
Manage your inventory - add, edit, and delete products easily.

**Sales Interface**
![Sales Screenshot](images/sales.png)
Process sales quickly with the shopping cart system and customer tracking.

**Transactions**
![Transactions Screenshot](images/transactions.png)
View all your sales history and print invoices whenever needed.

---

## What You Can Do With This

**Dashboard** - See what's happening in your business at a glance. You'll see how many products you have, total sales, revenue, and which items are running low on stock. It's like a quick health check of your shop.

**Manage Your Products** - Add new parts to your inventory, update prices and stock levels, and delete items that you no longer sell. You can organize everything by category like Brakes, Engine parts, Electrical, Filters, etc. There's also a search feature so you can find things quickly.

**Sell Parts Fast** - When a customer comes in, just search for what they want, add it to the cart, and process the payment. You can track customer info, apply discounts, and support multiple payment methods - cash, card, or bank transfer.

**Keep Records** - Every sale is saved automatically. You can look back at all your transactions, see who bought what, and print out professional invoices whenever you need them.

## What You Need

You'll need Apache (from XAMPP), MySQL database, and PHP 7.4 or higher. Any modern browser will work - Chrome, Firefox, Edge, whatever you use.

## Getting It Running

**Step 1: Put the files in the right place**
Extract everything to `C:\xampp\htdocs\autoparts\`

**Step 2: Set up the database**
- Open XAMPP and start Apache and MySQL
- Go to `http://localhost/phpmyadmin`
- Create a new database called `autoparts_db`
- Import the `autopart.sql` file to create the tables
- Then import `insert_products.sql` to add all the sample products

**Step 3: Check the database settings**
Open `php/config.php` and make sure it matches your setup:
- Host: localhost
- Database name: autoparts_db
- Username: root
- Password: (leave empty if you didn't set one)

**Step 4: Start and go**
Make sure Apache and MySQL are running in XAMPP, then open your browser and go to:
`http://localhost/autoparts/php/index.php`

That's it! You should see the dashboard.

## How to Use It

**When you first open the app**, you'll see the dashboard with all your stats - total products, revenue, and sales history.

**To add new products**, go to the Parts menu. Fill in the part number, name, category, price, and how many you have in stock. You can also add a description if you want.

**To sell something**, click on Sell. Search for what the customer wants, click "Add to Cart", add as many items as needed. Then put in their name and phone (optional), pick how they're paying, and hit Complete Sale. The system automatically prints an invoice number.

**To check what you've sold**, go to Transactions. You can see everything that's been sold, how much money came in, and print out invoices anytime.

**Stock levels** update automatically when you make a sale, so you don't have to worry about manually updating inventory.

All prices are in **TZS** - Tanzanian Shillings. We've loaded realistic prices for different autoparts so you can get started right away.

## Files and Folders

The `php` folder has all the pages - dashboard, products, sales, transactions. The `css` folder has the styling to make everything look nice. There's also a `javascript` folder for the interactive parts. The SQL files are what set up your database.

## A Few Things to Know

- Everything you sell gets recorded automatically
- Invoices are generated with dates and invoice numbers
- Stock updates happen instantly after a sale
- You can track multiple payment types
- All data is kept safe in the MySQL database
