# Courier-olza

![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat) [![codecov.io](https://codecov.io/github/sylapi/courier-olza/coverage.svg)](https://codecov.io/github/sylapi/courier-olza/)

## Methody

### Init

```php

```

### CreateShipment

```php

```

### PostShipment

```php

```

### GetStatus

```php

```

### GetLabel

```php

```

# Zásilkovna (miejsce odbioru)

### Init

```php

```

Dostępne są dwa sposoby, jak można wdrożyć punkty odbioru z grupy Packeta w sklepie internetowym:

1. Gotowy widget https://widget.packeta.com/v5/#/

Packeta udostępnia w tym celu konfigurator, który można wykorzystać do wdrożenia punktów odbioru.

- Konfigurator https://widget.packeta.com/www/configurator/

- Konfigurator z użyciem własnych stylów CSS https://widget.packeta.com/www/style-configurator/

Dzięki niemu można zdefiniować właściwości widgetu, który pojawi się na stronie i zdobyć kod do podpięcia w koszyku zakupowym.

Manual dotyczący implementacji widgetu znajdziemy pod linkiem: https://docs.packetery.com/01-pickup-point-selection/02-widget-v6.html

2. Lista punktów z pliku online

Można skonfigurować pola wyboru punktów odbioru we własnym zakresie, można posłużyć się plikiem online, który zawiera listę wszystkich punktów odbioru niezależnie od kraju docelowego. Dostępne formaty plików oraz adresy URL są opisane w dokumentacji Packeta pod tym linkiem https://docs.packetery.com/01-pickup-point-selection/04-branch-export-v4.html

Informacje dotyczące zarządzanie punktami odbioru: https://docs.packetery.com/05-eshop-providers/01-eshop-provider.html#toc-pick-up-point-management

## ENUMS

### ShipmentType

| WARTOŚĆ | OPIS |
| ------ | ------ |
| DIRECT | Bezpośrednia przesyłka |
| WAREHOUSE | Z magazynu OlzaLogistic |

### SpeditionCode

| WARTOŚĆ | OPIS |
| ------ | ------ |
| GLS | GLS |
| CP | Poczta Czeska - list polecony |
| CP-RR | Poczta Czeska - list polecony |
| CP-NP | Poczta Czeska - paczka na pocztę |
| SP | Poczta Słowacka - kurier ekspresowy |
| DPD | DPD |
| PPL | PPL - paleta |
| ZAS | Zásilkovna - miejsce odbioru |
| ZAS-P | Zásilkovna - przesyłka pocztowa na adres |
| ZAS-K | Zásilkovna - przesyłka kurierska na adres |
| ZAS-D | Zásilkovna - na adres DPD |
| ZAS-C | Zásilkovna - Cargus |
| ZAS-B | Zásilkovna - best delivery |
| ZAS-COL | Coletaria - pick-up point |
| GEIS-P | Geis - przesyłka na palecie |
| BMCG-IPK | InPost Kuriér |
| BMCG-IPKP | InPost - Paczkomaty |
| BMCG-DHL | DHL |
| BMCG-PPK | Pocztex Kurier 48 |
| BMCG-PPE | Pocztex Ekspres 24 |
| BMCG-UC | Cargus |
| BMCG-HUP | Maďarská Pošta - business parcel |
| BMCG-FAN | FAN Courier - home delivery |
| BMCG-INT |  WE|DO (In Time) |
| BMCG-INT-PP | WE|DO (In Time) - výdejní |
| ZAS-ECONT-HD | Econt - home delivery |
| ZAS-ECONT-PP | Econt - pick-up point |
| ZAS-ACS-HD | ACS - home delivery |
| ZAS-ACS-PP | ACS - pick-up point |
| ZAS-SPEEDY-PP | Speedy - pick-up point |
| ZAS-SPEEDY-HD | Speedy - home delivery |


## Komendy

| KOMENDA | OPIS |
| ------ | ------ |
| composer tests | Testy |
| composer phpstan |  PHPStan |
| composer coverage | PHPUnit Coverage |
| composer coverage-html | PHPUnit Coverage HTML (DIR: ./coverage/) |
