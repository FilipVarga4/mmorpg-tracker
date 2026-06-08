# Semestrálne zadanie z predmetu Skriptovacie jazyky

**Téma:** MMORPG BiS Tracker (pre hru SWTOR)  
**Autor:** Filip Varga

## O čom je projekt?
Tento projekt je webová aplikácia, ktorá slúži ako tracker výbavy pre hráčov MMORPG Star Wars: The Old Republic. Sleduje aktuálny Item Rating postáv a počíta, koľko percent im chýba do dosiahnutia maximálneho herného stropu (BiS - Best in Slot, čo je rating 343). Aplikácia je určená hlavne pre Raid Leaderov, ktorí potrebujú vidieť pripravenosť celej súpisky na endgame obsah.

## Splnené kritériá zadania

### Základné požiadavky:
* **OOP architektúra:** Celý projekt je napísaný objektovo. Využívam vlastné triedy pre objekty postáv (`Character`) a používateľov (`User`). Komunikáciu s databázou riešia samostatné repozitáre.
* **CRUD operácie:** V aplikácii sa dajú postavy kompletne spravovať:
    * Vytvorenie novej postavy (`add_character.php`)
    * Výpis, vyhľadávanie a štatistiky (`index.php`)
    * Úprava dát a progresu (`edit_character.php`)
    * Mazanie postáv z databázy (`delete_character.php`)
* **Čisté PHP:** Nepoužil som žiadny framework ani CMS systém, všetko je písané v natívnom PHP 8.
* **Autoloader:** Načítavanie tried beží automaticky cez `spl_autoload_register` v súbore `autoload.php`.

### Plusové body a optimalizácie:
* **Zabezpečené prihlásenie:** Sekcia pre úpravu a pridávanie postáv je prístupná len po prihlásení. Heslá sú v databáze hashované pomocou `password_hash()`. Pri úspešnom prihlásení generujem nové session ID cez `session_regenerate_id(true)` ako ochranu relácie.
* **Ochrana proti XSS a SQL Injection:** Databázové dopyty používajú PDO prepared statements. Výstupy do HTML sú ošetrené funkciou `htmlspecialchars()`.
* **Dynamické prvky a JavaScript:** Pri pridávaní postavy sa pomocou JavaScriptu mení ponuka disciplín v dropdown menu podľa toho, či si používateľ vybral frakciu Sith Empire alebo Galactic Republic (stránka sa kvôli tomu nemusí znova načítavať).
* **Stránkovanie (Pagination):** Hlavná tabuľka s postavami je stránkovaná po 25 záznamov na stranu. Filtre vo formulári sa pri prepínaní stránok nemažú, ale prenášajú sa v URL.

## Inštalácia a spustenie

1. Skopírujte súbory projektu do zložky vášho lokálneho servera (XAMPP, Apache a pod.).
2. V MySQL/MariaDB vytvorte databázu a importujte tabuľky:

```sql
CREATE DATABASE mmorpg_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tracker_user'@'localhost' IDENTIFIED BY 'tracker123';
GRANT ALL PRIVILEGES ON mmorpg_tracker.* TO 'tracker_user'@'localhost';
FLUSH PRIVILEGES;

USE mmorpg_tracker;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    char_name VARCHAR(100) NOT NULL,
    combat_style VARCHAR(100) NOT NULL,
    gear_rating INT NOT NULL,
    target_rating INT NOT NULL DEFAULT 343,
    faction VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
3. Otvorte aplikáciu v prehliadači a jednorazovo spustite inicializačný skript `create_admin.php` (napr. `http://localhost/create_admin.php`). Tento skript bezpečne vytvorí predvoleného administrátorského používateľa, ak tabuľka `users` zatiaľ neobsahuje žiadne záznamy.
* **Prihlasovacie údaje:** `admin` / `admin123`

4. Po úspešnej inicializácii skript `create_admin.php` z bezpečnostných dôvodov zmažte alebo k nemu zablokujte prístup.