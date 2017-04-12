Everything in the folder should be in MAMP/htdocs, except 'db'-folder, which should be in MAMP/db/mysql/SecuriShop

- - - - - -

TODO:
* Toggle buttons or checkboxes, for global variables to work in all forms
* More OWASP-stuff?

- - - - - -

MAMP, open WebStart Page for phpMyAdmin
Use SHA-512 for passwords, passwords can be 128 characters long.


- - - - - -

Below are examples of the two tables:

Users
CREATE TABLE `user` ( `id` INT(4) NOT NULL , `username` VARCHAR(20) NOT NULL , `password` VARCHAR(128) NOT NULL , `hashpass` VARCHAR(128) NOT NULL , `cred` INT(2) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `username`, `password`, `hashpass`, `cred`) VALUES
(0,'admin','adminpassword,'eae889ceda1452b34555b2b52b9f05d28a1e8ed8d5dc8c62362b90ee49746af1b99bf53cb3e58323d29c1dcc5b1203e45f824d10d87b1a63b9d6eec59a2f6740', 0);


Articles
CREATE TABLE `items` (
  `id` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `imgurl` varchar(255) NOT NULL,
  `price` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `items` (`id`, `name`, `imgurl`, `price`) VALUES
(0, 'Hemming Slimming', 'http://gloimg.twinkledeals.com/td/2015/201507/source-img/1436466658259-P-2819655.jpg?20141203001', 149.00);

- - - - - -

Cart structure:
Array consisting of product name + price.
$_SESSION['cart_products'] have
[prod_id]
[prod_name]
[price]
[amount]