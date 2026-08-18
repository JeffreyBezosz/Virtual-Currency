# Virtual XD Currency

Virtual XD Currency is een eenvoudige webapplicatie waarmee studenten tokens naar elkaar kunnen sturen.

## Functionaliteiten

- [x] Registreren met een `@student.thomasmore.be` e-mailadres
- [x] Inloggen en veilig uitloggen
- [x] Nieuwe gebruiker start met 10 tokens
- [x] Huidig saldo bekijken
- [x] Andere gebruikers zoeken op naam of e-mail
- [x] Gebruikers zoeken zonder page refresh met `fetch()`
- [x] Tokens versturen met een reden
- [x] Saldo van afzender en ontvanger aanpassen
- [x] Ontvangen en verstuurde transacties bekijken
- [x] Details van één transactie bekijken
- [x] Saldo automatisch vernieuwen met `fetch()`

## Technische vereisten

- [x] PHP zonder framework
- [x] MySQL/MariaDB met tabellen `users` en `transactions`
- [x] PDO met prepared statements
- [x] OOP met de classes `Database`, `User` en `Transaction`
- [x] SQL-query's staan alleen in de backendclasses
- [x] PHP sessions voor authenticatie
- [x] HTML, gewone CSS en JavaScript
- [x] Databasebestand aanwezig in `database.sql`
- [x] Voorbeeldconfiguratie aanwezig in `config.example.php`
- [x] Echte databaseconfiguratie uitgesloten via `.gitignore`

## Beveiliging

- [x] Wachtwoorden gehasht met `password_hash()`
- [x] Wachtwoorden gecontroleerd met `password_verify()`
- [x] Bescherming tegen SQL-injection met prepared statements
- [x] Uitvoer beschermd tegen XSS met `htmlspecialchars()`
- [x] Formulieren beschermd met CSRF-tokens
- [x] Beveiligde pagina's alleen toegankelijk na inloggen
- [x] Transfers uitgevoerd met een database transaction
- [x] Alleen betrokken gebruikers kunnen transactiedetails bekijken

## Belangrijkste validaties

- [x] Alleen geldige studentenmailadressen
- [x] Wachtwoord van minstens 5 tekens
- [x] Geen dubbele e-mailadressen
- [x] Ontvanger moet bestaan
- [x] Geen tokens naar jezelf sturen
- [x] Minstens 1 token versturen
- [x] Geen hoger bedrag dan het beschikbare saldo
- [x] Reden is verplicht

## Projectlinks

**Online URL:** nog toe te voegen na deployment

**GitHub:** [github.com/JeffreyBezosz/Virtual-Currency](https://github.com/JeffreyBezosz/Virtual-Currency)

## Status voor indienen

- [x] Verplichte functionaliteiten lokaal getest
- [x] Databasewerking gecontroleerd
- [x] PHP-bestanden gecontroleerd op syntaxfouten
- [x] Geen databasewachtwoord opgeslagen in Git
- [ ] Applicatie online zetten
- [ ] Online URL hierboven invullen
- [ ] Definitieve werking online controleren
