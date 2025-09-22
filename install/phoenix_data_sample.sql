# $Id$
#
# CE Phoenix, E-Commerce made Easy
# https://phoenixcart.org
#
# Copyright (c) 2021 Phoenix Cart
#
# Released under the GNU General Public License
#
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!

INSERT INTO advert VALUES ('1', 'Our Farm', 'products_new.php', '', 'our-farm.webp', 'carousel', NOW(), NULL, '20', '1');
INSERT INTO advert VALUES ('2', 'Strawberries', '', '', 'strawberry.webp', 'carousel', NOW(), NULL, '10', '1');
INSERT INTO advert VALUES ('3', 'Lemons', 'index.php', 'cPath=1_4', 'fruit-8848715_640.webp', 'index', NOW(), NULL, '30', '1');
INSERT INTO advert VALUES ('4', 'Easy Ordering', 'privacy.php', '', 'laptop-7723139_640.webp', 'index', NOW(), NULL, '40', '1');

INSERT INTO advert_info VALUES ('1', '1', '<p class=\"fs-2 font-weight-bold fw-semibold mb-1\">Fresh fruit direct to your door</p>\r\n<p class=\"fs-4\">Grown with <i class="fas fa-heart fa-beat text-danger"></i> on our Devonshire Farm</p>\r\n<p><span class="btn btn-info">Click here to view our full range</span></p>');
INSERT INTO advert_info VALUES ('2', '1', '<p class=\"fs-2 font-weight-bold fw-semibold mb-1\">Strawberries Coming Soon</p>\r\n<p class=\"fs-4\">Pick Your Own at our Farm or delivered direct to your door</p>');
INSERT INTO advert_info VALUES ('3', '1', '<p class=\"fs-5 font-weight-bold fw-semibold card-title\">If life gives you lemons... make Juice!</p>\r\n<p class=\"card-text\">See our full range of Citrus Fruit now</p>');
INSERT INTO advert_info VALUES ('4', '1', '<p class=\"fs-5 font-weight-bold fw-semibold card-title\">Checkout easily using our 3 step checkout!</p>\r\n<p class=\"card-text\">Your details are kept safe and secure</p>');

UPDATE configuration SET configuration_value = 'carousel' WHERE configuration_key = 'MODULE_CONTENT_I_SLIDER_GRP';
UPDATE configuration SET configuration_value = 'index' WHERE configuration_key = 'I_ADVERTS_LINK';

UPDATE configuration SET configuration_value = '1;2' WHERE configuration_key = 'I_BRAND_ICONS_CSV';

INSERT INTO categories VALUES (1, 'sample/fruit.webp', 0, 10, NOW(), NULL);
INSERT INTO categories VALUES (2, 'sample/vegetables-1.webp', 0, 20, NOW(), NULL);
INSERT INTO categories VALUES (3, 'sample/apples-pears.webp', 1, 10, NOW(), NULL);
INSERT INTO categories VALUES (4, 'sample/citrus.webp', 1, 20, NOW(), NULL);

INSERT INTO categories_description VALUES ('1', '1', 'Fruit', 'Fruit contains many nutrients and vitamins essential to health.  Eating fruit is an essential part of a healthy balanced diet.', null, null);
INSERT INTO categories_description VALUES ('2', '1', 'Vegetables', null, null, null);
INSERT INTO categories_description VALUES (3, 1, 'Apples & Pears', 'Fresh and crisp with a variety of flavours.', NULL, NULL);
INSERT INTO categories_description VALUES (4, 1, 'Citrus Fruit', 'Tart, tangy and full of Vitamin C.', NULL, NULL);

INSERT INTO manufacturers VALUES (1, 'Fiacre', 'brands/fiacre.jpg', '', '', now(), null);
INSERT INTO manufacturers VALUES (2, 'Von Peacock', 'brands/von-peacock.jpg', '', '', now(), null);

INSERT INTO manufacturers_info VALUES (1, 1, '', 0, null, null, null, null);
INSERT INTO manufacturers_info VALUES (2, 1, '', 0, null, null, null, null);

INSERT INTO products VALUES (1, 1000, 'ORA-1', 'sample/orange-1.webp', 9.99, NOW(), NULL, NULL, 0.30, 1, 1, 1, 0, NULL, NULL);
INSERT INTO products VALUES (2, 1000, 'LEM-1', 'sample/lemon-1.webp', 7.99, NOW(), NULL, NULL, 0.30, 1, 1, 1, 0, NULL, NULL);
INSERT INTO products VALUES (3, 1000, 'PEA-1', 'sample/pear-1.webp', 4.99, NOW(), NULL, NULL, 0.30, 1, 1, 0, 0, NULL, NULL);
INSERT INTO products VALUES (4, 1000, 'APP-1', 'sample/apple-1.webp', 4.99, NOW(), NULL, NULL, 0.30, 1, 1, 2, 0, NULL, NULL);
INSERT INTO products VALUES (5, 1000, 'TOM-1', 'sample/tomatoes-1.webp', 1.99, NOW(), NULL, NULL, 0.50, 1, 1, 2, 0, NULL, NULL);
INSERT INTO products VALUES (6, 1, 'GTOM-1', 'sample/green-tomatoes-1.webp', 1.9900, NOW(), NULL, NULL, 0.50, 1, 0, 2, 0, NULL, NULL);
INSERT INTO products VALUES (7, 10, 'GAPP-1', 'sample/green-apple-1.webp', 4.9900, NOW(), NULL, NULL, 0, 1, 0, 2, 0, NULL, NULL);
INSERT INTO products VALUES (8, 0, 'GPF-1', 'sample/grapefruit-1.webp', 8.9900, NOW(), NULL, NOW() + INTERVAL 90 DAY, 0.40, 1, 0, 2, 0, NULL, NULL);
INSERT INTO products VALUES (9, 1000, 'LIM-1', 'sample/lime-1.webp', 8.4900, NOW(), NULL, NULL, 0.35, 1, 0, 2, 0, NULL, NULL);

INSERT INTO products_description VALUES (1, 1, '<p>Our oranges are a versatile fruit that can be enjoyed in many ways. They have a naturally balanced flavor and contain vitamin C, fibre, and other nutrients.</p><p>They can be eaten on their own, squeezed for juice, or used as an ingredient in cooking and baking. The zest and juice can add flavor to both sweet and savory dishes, while the fruit segments can be included in salads or served as a snack.</p><p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Eelffica</a> from <a href=\"https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Pixabay</a></p>\r\n\r\n<ul class="list-unstyled"><li>A great source of vitamin C and dietary fibre</li><li>Suitable for snacking, juicing, and cooking</li><li>Available in a range of sizes and varieties</li></ul>', '', 'Oranges are not the only fruit...', null, null);
INSERT INTO products_description VALUES (2, 1, 'Lemons', '<p>Our lemons are a staple citrus fruit commonly used for both their juice and zest. They provide a naturally tart flavour and are valued as a kitchen essential.</p><p>Lemons are often used to add acidity and freshness to a wide range of recipes. They can be incorporated into dressings, sauces, and marinades, or used in baking and desserts. A slice of lemon is also a familiar addition to drinks and teas.</p><p>Images by <a href=\"https://pixabay.com/users/Eelffica-52436/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Eelffica</a> from <a href=\"https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Pixabay</a> and <a href="https://pixabay.com/users/alles-2597842/?utm_source=link-attribution&utm_medium=referral&utm_campaign=image&utm_content=5069648">Alexander Lesnitsky</a> from <a href="https://pixabay.com//?utm_source=link-attribution&utm_medium=referral&utm_campaign=image&utm_content=5069648">Pixabay</a></p><ul class="list-unstyled"><li>Fresh citrus fruit</li><li>Source of vitamin C</li><li>Used for flavouring food and drinks</li><li>Available in various sizes</li></ul>', '', 'When life gives you lemons...', null, null);
INSERT INTO products_description VALUES (3, 1, 'Pears', '<p>Our pears are a soft and naturally sweet fruit that can be enjoyed fresh or used in cooking. They contain fibre, vitamin C, and other nutrients.</p><p>Pears can be eaten as a simple snack, sliced into salads, or cooked into both sweet and savoury dishes. They are also commonly used in baking, preserves, and desserts. Depending on the variety, pears may be enjoyed crisp or left to ripen for a softer texture.</p><p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Eelffica</a> from <a href=\"https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh fruit</li><li>Source of fibre and vitamin C</li><li>Suitable for eating fresh, cooking, and baking</li><li>Available in different varieties and sizes</li></ul>', '', 'Best things always come in pairs...', null, null);
INSERT INTO products_description VALUES (4, 1, 'Shiny Red Apples', '<p>Our shiny red apples are a crisp fruit with a naturally sweet flavour. They provide fibre, vitamin C, and other nutrients, making them suitable for everyday use.</p><p>They can be eaten fresh, sliced into salads, or used in cooking and baking. Apples are also commonly pressed for juice or cooked down into sauces and preserves. Their firm texture makes them a versatile choice for a range of recipes.</p></p>\r\n\r\n<p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Eelffica</a> from <a href=\"https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=756390\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh red apples</li><li>Source of fibre and vitamin C</li><li>Suitable for snacking, juicing, and cooking</li><li>Available in a variety of sizes</li></ul>', '', 'An apple a day keeps the doc away...', null, null);
INSERT INTO products_description VALUES (5, 1, 'Tomatoes', '<p>Our red tomatoes are a versatile vegetable that can be used in a variety of dishes. They have a balanced flavour and contain vitamin C, fibre, and other nutrients.</p><p>Tomatoes can be eaten raw in salads and sandwiches, cooked in sauces, soups, or stews, or used as a topping for various dishes. They are also commonly used in sauces, juices, and preserves. Their soft texture when ripe makes them suitable for both fresh and cooked recipes.</p><p>Image by <a href=\"https://pixabay.com/users/Rocky_H-11790006/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=4035459\">Rocky_H</a> from <a href=\"https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=4035459\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh red tomatoes</li><li>Source of vitamin C and fibre</li><li>Suitable for eating fresh, cooking, and making sauces</li><li>Available in different sizes</li></ul>', '', null, null, null);
INSERT INTO products_description VALUES (6, 1, 'Green Tomatoes', '<p>Our green tomatoes are firm and slightly tart, making them suitable for a range of culinary uses. They provide vitamin C, fibre, and other nutrients.</p><p>Green tomatoes are often used in cooking, including frying, pickling, or adding to stews and sauces. They can also be sliced into salads or served as part of cooked dishes where a firmer texture and tangy flavour are desired. Their acidity makes them a versatile ingredient in both traditional and contemporary recipes.</p><p>Image by <a href=\"https://pixabay.com/users/Rocky_H-11790006/\">Rocky_H</a> from <a href=\"https://pixabay.com/\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh green tomatoes</li><li>Source of vitamin C and fibre</li><li>Suitable for cooking, pickling, and salads</li><li>Available in a variety of sizes</li></ul>', '', NULL, NULL, NULL);
INSERT INTO products_description VALUES (7, 1, 'Green Apples', '<p>Our green apples are firm and crisp, with a naturally tangy flavour. They are a source of fibre and vitamin C, suitable for a variety of uses in the kitchen.</p><p>Green apples are commonly used in cooking and baking, including pies, tarts, sauces, and preserves. They can also be eaten fresh, sliced into salads, or added to drinks and smoothies. Their tartness makes them a popular choice for recipes that benefit from a sharper flavour.</p><p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/\">Eelffica</a> from <a href=\"https://pixabay.com/\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh green apples</li><li>Source of fibre and vitamin C</li><li>Suitable for cooking, baking, and fresh eating</li><li>Available in different sizes</li></ul>', '', 'Green apples, fresh and crisp with a fragrant flavour.', NULL, NULL);
INSERT INTO products_description VALUES (8, 1, 'Grapefruit', '<p>Our grapefruit is a citrus fruit with a slightly bitter and tangy flavour. It contains vitamin C, fibre, and other nutrients, making it suitable for everyday use.</p><p>Grapefruit can be eaten fresh, segmented for salads, or juiced. Its juice is commonly used in drinks, breakfast dishes, and dressings. The fruit can also be incorporated into both sweet and savoury recipes, where a slightly bitter citrus note is desired.</p><p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/\">Eelffica</a> from <a href=\"https://pixabay.com/\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh citrus fruit</li><li>Source of vitamin C and fibre</li><li>Suitable for eating fresh, juicing, and cooking</li><li>Available in various sizes</li></ul>', '', 'Big and juicy grapefruit', NULL, NULL);
INSERT INTO products_description VALUES (9, 1, 'Lime', '<p>Our limes are a small citrus fruit with a sharp, tangy flavour and a firm texture. They contain vitamin C and other nutrients and are commonly used to add brightness and acidity to a wide range of dishes.</p><p>Limes can be sliced, juiced, or zested to enhance the flavour of recipes. They are frequently used in dressings, marinades, sauces, and desserts, and are a popular addition to beverages and cocktails. The zest of a lime provides a concentrated citrus flavour, while the juice adds a fresh, tangy note.</p><p>In the kitchen, limes are valued for their versatility. They can balance sweetness in desserts, enhance the flavours of savoury dishes, or be used as a garnish to add colour and aroma. Limes are suitable for everyday use and can complement both simple and more complex recipes.</p><p>Image by <a href=\"https://pixabay.com/users/Eelffica-52436/\">Eelffica</a> from <a href=\"https://pixabay.com/\">Pixabay</a></p><ul class="list-unstyled"><li>Fresh citrus fruit</li><li>Source of vitamin C</li><li>Suitable for cooking, baking, drinks, and garnishes</li><li>Available in a range of sizes</li></ul>', '', 'Sharp and juicy, add a twist', NULL, NULL);

INSERT INTO products_attributes VALUES (1, 4, 1, 1, '0.0000', '+');
INSERT INTO products_attributes VALUES (2, 4, 1, 2, '5.0000', '+');
INSERT INTO products_attributes VALUES (3, 2, 1, 1, '0.0000', '+');
INSERT INTO products_attributes VALUES (4, 2, 1, 2, '4.5000', '+');
INSERT INTO products_attributes VALUES (5, 5, 1, 1, '0.0000', '+');
INSERT INTO products_attributes VALUES (6, 5, 1, 2, '4.9900', '+');
INSERT INTO products_attributes VALUES (7, 7, 2, 3, '0.0000', '+');

INSERT INTO products_attributes_download VALUES (7, 'apple-pie.zip', 7, 5);

INSERT INTO products_images VALUES (1, 2, 'sample/lemons-2.webp', '', 1);

INSERT INTO products_options VALUES (1, 1, 'Box Size', 10);
INSERT INTO products_options VALUES (2, 1, 'Download', 10);

INSERT INTO products_options_values VALUES (1, 1, '12', 10);
INSERT INTO products_options_values VALUES (2, 1, '24', 20);
INSERT INTO products_options_values VALUES (3, 1, 'apple-pie.zip', 10);


INSERT INTO products_options_values_to_products_options VALUES (1, 1, 1);
INSERT INTO products_options_values_to_products_options VALUES (2, 1, 2);
INSERT INTO products_options_values_to_products_options VALUES (3, 2, 3);

INSERT INTO products_to_categories VALUES (1, 4);
INSERT INTO products_to_categories VALUES (2, 4);
INSERT INTO products_to_categories VALUES (3, 3);
INSERT INTO products_to_categories VALUES (4, 3);
INSERT INTO products_to_categories VALUES (5, 2);
INSERT INTO products_to_categories VALUES (6, 2);
INSERT INTO products_to_categories VALUES (7, 3);
INSERT INTO products_to_categories VALUES (8, 4);
INSERT INTO products_to_categories VALUES (9, 4);

INSERT INTO reviews VALUES (1, 4, 0, 'John Doe', 5, NOW(), NULL, 1, 0, 'n');
INSERT INTO reviews_description VALUES (1, 1, 'Lovely box of crunchy apples and delivered very quickly.  Thank You!');

INSERT INTO testimonials VALUES (1, 0, 'John Doe', NOW(), NULL, 1, 'n');
INSERT INTO testimonials_description VALUES (1, 1, 'Amazing service! The products arrived quickly and exceeded my expectations. Will definitely shop here again!');

INSERT INTO specials VALUES (1, 1, '2.9900', now(), null, null, null, '1');
