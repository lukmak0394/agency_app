
# Projekt: **lmdev_agency**

## Struktura projektu

### 1. **Pliki SQL**
- **queries.sql** – Zawiera zapytania sql do bazy danych.
  - **Uwaga**: Najpierw należy wykonać zapytanie tworzące bazę danych: `lmdev_agency`.

### 2. **Pliki JavaScript**
- **main.js** – plik .js obsługujący **DataTables** oraz pozostałe zadania w aplikacji.

### 3. **Katalog `/src`**
- Zawiera klasy wykorzystywane w projekcie.

### 4. **Katalog `/actions`**
  Skrypty obsługujące zadania HTTP. Wszystkie dane są zwracane w formacie JSON:
  - **client_add.php** – dodawanie klienta.
  - **clients_get.php** – pobranie listy klientów do wyświetlenia.
  - **contact_persons_get.php** – pobranie listy wszystkich osób kontaktowych.
  - **packages_get.php** – pobranie dostępnych pakietów.
  - **packages_get_currency.php** – pobranie dostępnych pakietów dla wybranej waluty (po zmianie waluty w formularzu dodawania klienta).

### 5. **Katalog `/configs`**
  Zawiera pliki konfiguracyjne:
  - **db_conf.php** – konfiguracja bazy danych.
  - **globalconst.php** – globalne stałe.
  - **helper_arrays.php** – tablice pomocnicze wykorzystywane przy dodawaniu i modyfikacji danych.

### 6. **Katalog `/layout`**
  Zawiera pliki bazowe dla układu strony:
  - **header.php** – część nagłówka, w tym arkusze stylów i otwierający tag `body`.
  - **footer.php** – domykający tag `body` i załadowane skrypty .js.
  - **nav.php** – nawigacja.

### 7. **Podstrony (w głównym katalogu)**
  - **index.php** – strona główna, wyświetlająca również listę klientów.
  - **clients-list.php** – lista wszystkich klientów.
  - **client-form.php** – formularz dodawania klienta.
  - **contacts-list.php** – lista wszystkich osób kontaktowych.
  - **packages-list.php** – lista dostępnych pakietów wraz z cenami.

## Instalacja

1. Skopiuj pliki na serwer.
2. Zaimportuj **queries.sql** do swojej bazy danych, wykonując zapytanie do utworzenia bazy danych `lmdev_agency`.
3. Skonfiguruj połączenie z bazą danych w pliku **/configs/db_conf.php**.
5. Zmień stała APP_URL w pliku globalconst.php oraz main.js
4. Uruchom aplikację w przeglądarce.

## Technologie

- **PHP** – backend.
- **MySQL** – baza danych.
- **JavaScript (jQuery, DataTables)** – frontend.

