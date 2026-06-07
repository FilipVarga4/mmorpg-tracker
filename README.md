# Semestrálne zadanie zo Skriptovacích jazykov - SWTOR BiS Tracker

Tento projekt je moje semestrálne zadanie, ktoré slúži ako tracker progresie výbavy (Best in Slot) pre hráčov v hre Star Wars: The Old Republic (SWTOR). Webová aplikácia sleduje herný Item Rating postáv a dynamicky prepočítava ich percentuálny progres k maximálnemu stropu 343.

## Čo aplikácia robí a ako funguje

Projekt bol upravený tak, aby reálne zodpovedal mechanikám hry a správne spracovával skriptované dáta:

* **Rozdelenie podľa frakcií a disciplín:** V aplikácii sú rozdelené herné špecializácie pre Sith Empire a Galactic Republic, keďže každá strana má iné názvy rovnakých tried.
* **Automatické priradenie role skriptom:** Keď sa postava ukladá, PHP skript (`RoleMapper`) na pozadí automaticky zistí vybranú disciplínu a priradí jej rolu (Tank / DPS / Healer). Vďaka tomu nie je potrebné rolu zadávať manuálne.
* **Validácia ratingov (Max 343):** Formuláre na backende nepustia hodnotu ratingu, ktorá by bola vyššia ako maximálny herný strop 343.
* **Filtrovanie s súpiskou:** Na hlavnej stránke (`index.php`) je dashboard so štatistikami (počet postáv, priemer a maximum) a GET formulár. Dátami v tabuľke sa dá hýbať – dá sa filtrovať podľa mena, frakcie, role a konkrétnej disciplíny. JavaScript zabezpečuje, že vybraná hodnota zostane v selecte označená aj po kliknutí na tlačidlo "Aplikovať".
* **Progress bary a farby:** Progres výbavy postavy sa prepočítava v PHP a vizuálne sa renderuje ako CSS progress bar. Tabuľka je farebne rozlíšená podľa rolí (Tank fialovou, Healer zelenou, DPS červenou) v tmavom hernom štýle.

## Použité technológie a skripty

Projekt je napísaný bez akýchkoľvek frameworkov, aby bolo vidieť prácu s čistým kódom.

* **PHP (Backend):** Používam objektovo-orientovaný prístup. Kód je rozdelený na entity (`Character.php`, `User.php`) a repozitáre (`CharacterRepository.php`, `UserRepository.php`), ktoré sa starajú o SQL dopyty cez PDO a prepared statements. Využívam aj automatické načítavanie tried cez `spl_autoload_register` v `autoload.php`.
* **Zabezpečenie relácií (Sessions):** Pridávanie, úprava a mazanie (`add_character.php`, `edit_character.php`, `delete_character.php`) sú skriptom chránené. Ak človek nie je prihlásený, PHP ho nepustí ďalej a presmeruje na `login.php`. Bežný návštevník vidí iba hlavnú tabuľku s filtrami (read-only).
* **Hashovanie:** Administrátorské heslo sa v databáze neukladá ako čistý text, ale skript ho hashuje cez `password_hash` pomocou algoritmu BCRYPT. Výpisy sú ošetrené cez `htmlspecialchars()` kvôli bezpečnosti proti XSS.
* **Klientsky JavaScript (Frontend):** Vo formulároch na pridanie a úpravu je použitý čistý JavaScript. Keď používateľ klikne na zmenu frakcie, JS okamžite vyfiltruje a prepne možnosti v dropdown menu pre disciplíny (ponúkne len tie, ktoré patria danej frakcii) bez toho, aby sa musela znova načítavať celá stránka.

## Štruktúra súborov

* `css/style.css` - kompletný štýl pre tmavý herný režim (Dark mode).
* `src/` - PHP triedy, repozitáre na prácu s DB a automatický maper rolí.
* `templates/` - spoločné šablóny (header, footer, style_options).
* Hlavný adresár - skripty pre jednotlivé podstránky (index, add, edit, delete, login, logout, create_admin, autoload).

## Ako to spustiť lokálne

1. Stiahnuť zložku projektu a dať ju do adresára web servera (napr. `/srv/http/mmorpg-tracker`).
2. V MariaDB vytvoriť databázu a spustiť SQL príkazy na tabuľky:

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
);

CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    char_name VARCHAR(100) NOT NULL,
    combat_style VARCHAR(100) NOT NULL,
    gear_rating INT NOT NULL,
    target_rating INT NOT NULL DEFAULT 343,
    faction VARCHAR(20) NOT NULL DEFAULT 'Empire',
    role VARCHAR(10) NOT NULL DEFAULT 'DPS'
);