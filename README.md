<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# Mario Kart Tournament App

## Overview
The **Mario Kart Tournament App** is designed to facilitate competitive Mario Kart matches with structured vetoing, match tracking, and scoring mechanisms. This app enables admins to create and manage matches while allowing players to participate and view their stats.

---

## 🚀 Features
### 🔑 User Authentication & Roles
#### **Admin**
- ✅ Creates and manages users.
- ✅ Controls match setup, scoring, and progress.
- ✅ Can log in as either **Admin** or **Player**.

#### **Player**
- ✅ Views personal match history and statistics.
- ✅ Participates in matches.
- ✅ Can change their password.

### 🎮 Match System
- **Cup Veto Process**: Players take turns banning and picking cups before a match.
- **Match Tracking**:
  - 📌 Displays current races, scores, and standings.
  - 📊 Players' placements are recorded after each race.
- **Scoring System**:
  - 🏆 **BO1 (Best of 1)** → First to win 1 cup.
  - 🏆 **BO3 (Best of 3)** → First to win 2 cups.
- **Race & Cup Modals**:
  - 🎨 Interactive UI displaying race details and track layouts.

---

## 🛠 Installation & Setup
### 📌 Prerequisites
- PHP 8+
- Composer
- Node.js & NPM
- MySQL / PostgreSQL

### 🔧 Steps
1. **Clone the repository**:
   ```sh
   git clone <repository-link>
   cd bleja-laravel
   ```
2. **Install dependencies**:
   ```sh
   composer install
   npm install && npm run dev
   ```
3. **Set up the database**:
   - Create a `.env` file (or copy `.env.example`).
   - Update database credentials.
   ```sh
   php artisan migrate --seed
   ```
4. **Generate the application key**:
   ```sh
   php artisan key:generate
   ```
5. **Link the storage folder** (required for uploads & saved files):
   ```sh
   php artisan storage:link
   ```
6. **Run the application**:
   ```sh
   php artisan serve
   ```

---

## 🔄 Resetting & Reseeding the Database
If you need to completely reset and reseed the database, use:
```sh
php artisan migrate:fresh --seed
```
> ⚠️ This **deletes all existing data** and creates a fresh database with test users.

---

## 🔑 Default Admin Credentials
To test admin functionality, log in with:
- **👤 Username**: `lunko2000`
- **🔒 Password**: `lunko2000`

> *(You can change these credentials after logging in.)*

---

## ⚙️ Tech Stack
- **Backend**: Laravel 10, PHP 8, MySQL/PostgreSQL
- **Frontend**: Blade, Tailwind CSS (if applicable)
- **Authentication**: Laravel Breeze

---

## 📈 Future Plans
- 🔹 Implement **Player Stats Page**.
- 🔹 Introduce **new game types** (Darts, 8Ball Pool).
- 🔹 Improve **match scheduling and ranking system**.

---

## 🤝 Contributing
This app is a **private project**, but contributions & feedback are welcome!

---

## 📜 License
MIT License. Free to use and modify.

---

## 📬 Contact
For inquiries, feel free to reach out to the project owner!

---