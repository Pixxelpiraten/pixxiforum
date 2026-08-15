# PixxiForum

Ein kleines Forum in reinem PHP + MySQL — Login/Registrierung, Threads &
Posts nach Kategorien, Live-Chat, private Nachrichten und ein Admin-Bereich.

## Features

- Registrierung & Login (Passwörter mit bcrypt gehasht)
- Foren-Kategorien, Threads, Posts
- Live-Chat
- Private Nachrichten
- Admin-Dashboard zur Nutzerverwaltung

## Stack

PHP (`mysqli`), MySQL, Vanilla JS/CSS — keine Frameworks, keine Build-Schritte.

## Setup

1. XAMPP (oder eine andere PHP+MySQL-Umgebung) starten
2. Projekt nach `htdocs/pixxiforum` legen
3. `setup.php` im Browser aufrufen — legt Datenbank, Tabellen und einen
   Admin-Account an und zeigt einmalig ein zufällig generiertes
   Admin-Passwort an
4. **`setup.php` danach vom Server löschen**
5. `index.php` aufrufen

## Hinweis

`config/db.php` nutzt die XAMPP-Standardwerte (`root` ohne Passwort) für
die lokale Entwicklung — für einen echten Betrieb entsprechend anpassen.
