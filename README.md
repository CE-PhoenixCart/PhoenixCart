# CE-PhoenixCart

<p align="center">
  <img alt="Phoenix Cart logo" src="https://raw.githubusercontent.com/CE-PhoenixCart/PhoenixCart/master/.github/ce-phoenix.png">
</p>

**Phoenix Cart** is a free, open-source, self-hosted PHP e-commerce platform with a hook system for customisation without core changes.

## Table of Contents

* [What is Phoenix](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#description)
  - [Demo Sites](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#demo-sites)
* [Why Phoenix?](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#why-phoenix)
  - [Is Phoenix right for you?](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#is-phoenix-right-for-you)
* [Installation](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#installation)
  - [One-click](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#one-click)
  - [Requirements](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#requirements)
  - [User Checklist](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#user-checklist)  
  - [Language Packs](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#language-packs)
* [Supporting the Project](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#how-to-support-the-phoenix-project)
  - [Certified Partners](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#certified-partners)
  - [Join the Phoenix Forum](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#join-the-phoenix-forum)
* [Links](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#helpful-links)
* [Credits](https://github.com/CE-PhoenixCart/PhoenixCart?tab=readme-ov-file#credits)

## Description

Phoenix is a powerful ecommerce shop ready to use out of the box, putting you online and in full control of your business right from the start.  Your customers will love the modern, responsive design that will not only make your website look great on all mobile viewing devices but also perform at speed whilst giving you the power to create an individual and unique look to your shop with just a few clicks!

Phoenix is packed with many first class utilities as standard but its modular software design lets you add many more with no programming skills required. The full suite of product, shipping and payment options included will let you sell thousands of products in any number of categories worldwide in any currency or language providing a seamless customer experience.

### Demo Sites

Version | URL
------- | ---
Shop side, using IGNIS Template|https://phoenixcart.org/demo/  
Admin side|https://phoenixcart.org/demo_admin/admin/

## Why Phoenix?

Phoenix Cart is a free, open-source, self-hosted e-commerce platform written in PHP. It is an alternative to hosted SaaS platforms such as Shopify and to plugin-based stacks such as WooCommerce, for merchants and developers who want to own their store, their code and their data.

**A self-hosted alternative to Shopify**
- No monthly platform fee. You host it, you own it.
- Your store data lives in your own MySQL/MariaDB database.
- Payment providers still charge their own processing fees, but the platform takes no cut.

**A standalone alternative to WooCommerce**
- A complete shopping cart in its own right, with no CMS or plugin stack required underneath it.
- Sell in any currency or language, with Bootstrap 5 and vanilla JavaScript on the frontend.

**The modern, maintained successor to osCommerce**
- Phoenix began as a fork of osCommerce 2.3.x and has been developed as its own platform since.
- Runs on current PHP versions (7.4 to 8.3 tested), with a responsive Bootstrap 5 frontend & backend.
- The hook system and the `no core changes` principle mean you customise without editing core files.
- A maintained platform for existing osCommerce store owners to move to.

**Open source and community driven**
- Actively developed and maintained by an open-source community.

**Built for customisation without core changes**
- A hook system lets you extend and modify behaviour without editing core files.
- Following the `no core changes` principle keeps your customisations separate from the core code.

### Is Phoenix right for you?

Phoenix is a good fit if you:
- want a self-hosted online store with no monthly subscription
- want to be online in a few minutes, including one-click installs through Softaculous or Installatron
- are a PHP developer who wants to customise a store without forking it
- are running an older osCommerce store and want a maintained successor

Phoenix may not be the best fit if you want a fully managed, hosted service where someone else looks after servers, updates and security for you.

## Installation

Installation of Phoenix takes no more than a few minutes - you will need a hosting account that supports PHP (programming language) and has at least one SQL database.  Phoenix can even be installed on your home computer for testing purposes.

### One-click

Phoenix can now be installed with just `one click` via [Softaculous](http://www.softaculous.com/apps/ecommerce/CE_Phoenix) or [Installatron](https://installatron.com/cephoenixcart/)

### Requirements

Software | Minimum | Maximum (tested)
-------- | ------- | ----------------
PHP | 7.4 | 8.3
MariaDB | 10.2.2 | 10.5
MySQL | 5.7.7 | 8.0

Only one of MySQL or MariaDB is needed.  

Type | Name | Value
---- | ---- | -----
PHP setting | allow_url_fopen | ON
PHP extension | MySQLi | enabled
PHP extension | intl | enabled
PHP setting | session.use_trans_sid | OFF
PHP setting | session.auto_start | OFF
PHP setting | file_uploads | ON

Internationalization of date names will not work properly without intl enabled.

### User Checklist

- [ ] download Phoenix & perform installation
- [ ] check security page in administrative area;  
      admin > tools > security checks
- [ ] join Phoenix Forum
- [ ] install modules;  
      admin > modules > navbar<br>
      admin > modules > content<br>
      admin > modules > layout<br>
      admin > modules > boxes<br>
      admin > modules > shipping<br>
      admin > modules > payment
- [ ] perform a test checkout
- [ ] load your categories and products

### Language Packs

See the list at https://phoenixcart.org/forum/app.php/addons/free/language-23

Please be aware that most language packs are maintained by volunteers so may not be fully up to date.

## How to Support the Phoenix Project

Help Phoenix fly high...if you or your employer is commercially dependent on Phoenix (or a previous incarnation), please help to sponsor forward movement in the code-base. Phoenix needs you as much as you need Phoenix.

Thank you to all shopowners, developers, consultants and business owners who are supporting the Project by volunteering their time and/or by supporting the project financially.

### Certified Partners

[Certified Partners](https://phoenixcart.org/forum/app.php/developers) are those who are known to produce modern code, adhering as much as possible to the Phoenix core principle of `no core changes`. They also provide services such as SEO, [hosting](https://phoenixcart.org/forum/app.php/hosting), theme design and more.

* These partners are certified by the Core Team
* These partners support Phoenix by giving their time, code and financial support

If you are looking for a developer for a paid-for project, please consider one of those at https://phoenixcart.org/forum/app.php/developers

### Join the Phoenix Forum

The [Phoenix Forum](https://phoenixcart.org/forum/) is the place to get help and advice. Ask questions, share what you're building, and learn from other Phoenix users. It's free to join.

### Take the Next Step: Join the Code Co-op

Once your store is live and earning, Phoenix becomes part of your business. The [Code Co-op](https://phoenixcart.org/code_coop.php) is where shop owners, developers and consultants who depend on Phoenix back its development and help steer where it goes next.

If your business relies on Phoenix, joining is the natural way to invest in the software it runs on. [Join the Code Co-op](https://phoenixcart.org/code_coop.php).

## Helpful Links

Channel | URL 
------------ | -------------
Phoenix (Forum) | https://phoenixcart.org/forum/
Phoenix (Youtube) | https://www.youtube.com/@PhoenixCart/
User Guide (Phoenix Cart) | https://phoenixcart.org/phoenixcartwiki/index.php
Add-ons Library (Phoenix Cart) | https://phoenixcart.org/forum/addons/

## Credits

Images in the default installation are copyright their respective owners;

Image | Owner | Usage
------------ | ------------- | -------------
Phoenix Logos | Phoenix Cart | https://phoenixcart.org/marketing_media.php
Oranges | [Peggychoucair](https://pixabay.com/users/peggychoucair-1130890/) from Pixabay | https://pixabay.com/service/license/
Lemons | [JillWellington](https://pixabay.com/users/jillwellington-334088/) from Pixabay | https://pixabay.com/service/license/
Lemons | [zuzi99](https://pixabay.com/users/zuzi99-7340598/) from Pixabay | https://pixabay.com/service/license/
Pears | [JillWellington](https://pixabay.com/users/jillwellington-334088/) from Pixabay | https://pixabay.com/service/license/
Red Apples | [manja18081988](https://pixabay.com/users/manja18081988-52314215/) from Pixabay | https://pixabay.com/service/license/
Red Tomatoes | [RitaE](https://pixabay.com/users/ritae-19628/) from Pixabay | https://pixabay.com/service/license/
Green Tomatoes | [HBH-MEDIA-photography](https://pixabay.com/users/hbh-media-photography-193359/) from Pixabay | https://pixabay.com/service/license/
Green Apples | [Daria-Yakovleva](https://pixabay.com/users/daria-yakovleva-3938704/) from Pixabay | https://pixabay.com/service/license/
Grapefruit | [blandinejoannic](https://pixabay.com/users/blandinejoannic-15617008/) from Pixabay | https://pixabay.com/service/license/
Lime | [Congerdesign](https://pixabay.com/users/congerdesign-509903/) from Pixabay | https://pixabay.com/service/license/
Tractor | [The_Northern_Photographer](https://pixabay.com/users/the_northern_photographer-49449853/) from Pixabay | https://pixabay.com/service/license/
Strawberries | [MariyaKas](https://pixabay.com/users/mariyakas-16732382/) from Pixabay | https://pixabay.com/service/license/
Fruit, Laptop | [Ylanite Koppens](https://pixabay.com/users/nietjuhart-30460544/) from Pixabay | https://pixabay.com/service/license/
Index Carousels | [congerdesign](https://pixabay.com/users/congerdesign-509903/) from Pixabay | https://pixabay.com/service/license/