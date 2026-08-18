# Virtual XD Currency

Een eenvoudige PHP-applicatie waarmee studenten een account kunnen maken, inloggen en tokens naar elkaar kunnen sturen.

## Benodigdheden

- PHP 7.4 of nieuwer met de PDO MySQL-extensie
- MySQL of MariaDB
- Een browser
- DBeaver voor het uitvoeren en bekijken van de database

## Lokaal instellen

1. Installeer bijvoorbeeld XAMPP als PHP en MySQL nog niet op je computer staan.
2. Start Apache en MySQL via XAMPP.
3. Open `database.sql` in DBeaver en voer het volledige script uit.
4. Kopieer `config.example.php` naar `config.php`.
5. Vul in `config.php` je lokale databasegegevens in.

De echte `config.php` staat in `.gitignore` en komt dus niet op GitHub.

## Applicatie starten

Open een terminal in de projectmap en voer uit:

```text
php -S localhost:8000 -t public
```

Als je XAMPP gebruikt en `php` niet herkend wordt, gebruik dan:

```text
C:\xampp\php\php.exe -S localhost:8000 -t public
```

Open daarna `http://localhost:8000` in je browser.

## Handmatig testen

Gebruik minstens twee verschillende studentenaccounts.

1. Registreer een account met een adres dat eindigt op `@student.thomasmore.be`.
2. Controleer in DBeaver dat het account 10 tokens heeft en het wachtwoord gehasht is.
3. Test een dubbel e-mailadres, een fout domein en een te kort wachtwoord.
4. Login met een juist en een fout wachtwoord.
5. Controleer dat het dashboard zonder login niet bereikbaar is.
6. Stuur tokens naar het tweede account.
7. Test ook bedrag 0, een negatief bedrag, te weinig saldo en een transfer naar jezelf.
8. Controleer in DBeaver dat beide saldo's en het transactierecord juist zijn.
9. Bekijk de transactiegeschiedenis bij beide accounts.
10. Open de transactiedetails en probeer als derde gebruiker hetzelfde transactie-ID te openen.
11. Log uit en controleer dat beveiligde pagina's opnieuw naar de login sturen.
