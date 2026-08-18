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

**Online URL:** [https://virtualxdcurrency.infinityfree.me](https://virtualxdcurrency.infinityfree.me)

**GitHub:** [github.com/JeffreyBezosz/Virtual-Currency](https://github.com/JeffreyBezosz/Virtual-Currency)

De repository staat public en bevat geen echte databasewachtwoorden of andere security credentials.

## Testaccount

Gebruik dit account om snel te testen:

- **E-mail:** `user@user.com`
- **Wachtwoord:** `User`

Er is ook minstens een tweede gebruiker aanwezig zodat transfers getest kunnen worden.

## Zelfreflectie

**Score:** 17 / 20

Ik ben tevreden over mijn eindproject omdat de belangrijkste functies werken. Gebruikers kunnen registreren, inloggen, uitloggen, hun saldo bekijken en tokens sturen naar andere gebruikers. Ik heb ook geprobeerd om de code veilig te maken door PDO prepared statements te gebruiken tegen SQL-injection, `htmlspecialchars()` tegen XSS en bcrypt voor wachtwoorden.

Ik vind dat mijn project goed aansluit bij de opdracht, vooral omdat ik OOP gebruik met aparte klassen zoals `User`, `Transaction` en `Database`. Ook de AJAX-functionaliteiten zitten erin: gebruikers zoeken werkt zonder refresh en het saldo wordt automatisch vernieuwd.

Wat beter kon, is dat de layout nog professioneler en uitgebreider zou kunnen zijn. Ook de deployment naar InfinityFree heeft wat extra werk gevraagd, waardoor ik daar veel uit geleerd heb. Als ik meer tijd had, zou ik nog inkomende transacties automatisch in het overzicht laten verschijnen en de interface nog mooier afwerken. Toch zou ik als klant blij zijn met dit project, omdat de basisfunctionaliteiten werken en de app online testbaar is.

## Status voor indienen

- [x] Verplichte functionaliteiten lokaal getest
- [x] Databasewerking gecontroleerd
- [x] PHP-bestanden gecontroleerd op syntaxfouten
- [x] Geen databasewachtwoord opgeslagen in Git
- [x] Applicatie online gezet
- [x] Online URL hierboven ingevuld
- [x] Definitieve werking online gecontroleerd
