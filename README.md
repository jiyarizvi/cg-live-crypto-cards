# CG Live Crypto Cards

A lightweight, fast, and customizable WordPress plugin that displays live cryptocurrency price cards on any page or post using a simple shortcode.
Perfect for crypto blogs, news sites, and dashboards that need real‑time market data.

## 🚀 Features

Live price updates powered by a crypto API

Beautiful responsive cards that fit any theme

Multiple cryptocurrencies supported

Shortcode-based — add cards anywhere

Lightweight & optimized for speed

Customizable styles (colors, layout, card size)

No coding required

## 🧩 Installation

Download or clone this repository

Upload the plugin folder to:
wp-content/plugins/cg-live-crypto-cards/

Go to WordPress Admin → Plugins

Activate CG Live Crypto Cards

## 🛠️ Usage

Add the shortcode anywhere in your content:

```
[cg_live_crypto symbol="BTC"]
```
Supported attributes:
Attribute	Description	Example
symbol	Crypto symbol	BTC, ETH, SOL
theme	Card theme	light, dark
size	Card size	small, medium, large

Example with options:
```
[cg_live_crypto symbol="ETH" theme="dark" size="large"]
```
## 📡 Data Source

The plugin fetches live market data from a trusted cryptocurrency API such as:

CoinGecko

CoinMarketCap

CryptoCompare

(Your implementation may vary — update this section based on your actual API.)

## 🎨 Customization

You can override card styles by adding CSS to your theme:
```
css
.cg-crypto-card {
    border-radius: 10px;
    padding: 15px;
}
```
## 📦 Folder Structure

```
cg-live-crypto-cards/
│── assets/
│── includes/
│── templates/
│── cg-live-crypto-cards.php
│── README.md
```

## 🤝 Contributing

Pull requests are welcome.
For major changes, please open an issue first to discuss what you’d like to improve.

## 📄 License
This project is licensed under the MIT License — feel free to use and modify it.
