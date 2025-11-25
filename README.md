# Adaptive Media - Books API

Modern Laravel alapú REST API könyvek kezelésére, statisztikák generálására és valuta árfolyam konverzióra.

## 📖 Leírás

Ez a projekt egy teljes körű könyvkezelő API, amely lehetővé teszi könyvek kezelését, keresését és különböző statisztikák generálását. A rendszer cache-elt lekérdezéseket használ a teljesítmény optimalizálásához, valamint integrációt biztosít külső valuta árfolyam API-kkal.

## ✨ Főbb funkciók

- **Könyvek CRUD műveletei**: Könyvek listázása, létrehozása és lekérdezése
- **Keresés**: Többkritériumos keresés cím, szerző és kategória alapján
- **Statisztikák**: 
  - Drága könyvek (átlag feletti ár)
  - Népszerű kategóriák (top 3)
  - Top Fantasy & Sci-Fi könyvek
- **Valuta konverzió**: HUF → EUR konverzió
- **Cache optimalizáció**: Intelligens cache stratégia a teljesítmény javításához
- **Validáció**: Form Request alapú bemeneti validáció

## 🛠 Technológiai stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Adatbázis**: MySQL
- **Cache**: Database cache driver
- **API**: RESTful JSON API

## 📋 Követelmények

- PHP 8.2 vagy újabb
- Composer
- MySQL 5.7+ vagy MariaDB 10.3+

## 🚀 Telepítés

### 1. Projekt klónozása

```bash
git clone <repository-url>
cd adaptivemedia
```

### 2. Függőségek telepítése

```bash
composer install
```

### 3. Környezeti változók beállítása

```bash
cp .env.example .env
php artisan key:generate
```

A `.env.example` fájl sablonként szolgál. Állítsd be a saját környezeti változóidat a `.env` fájlban (adatbázis kapcsolat, API kulcsok, stb.).

### 4. Adatbázis beállítása

```bash
php artisan migrate
php artisan db:seed
```

### 5. Alkalmazás indítása

```bash
php artisan serve
```

Az API elérhető lesz: `http://localhost:8000/api`

## 📚 API Dokumentáció

### Base URL
```
http://localhost:8000/api
```

### Végpontok

#### Könyvek kezelése

**Összes könyv lekérdezése**
```
GET /books
```

**Válasz példa:**
```json
{
    "success": true,
    "message": "Books listed successfully",
    "data": [
        {
            "id": 1,
            "title": "Könyv címe",
            "author_id": 1,
            "author_name": "Szerző neve",
            "category_id": 1,
            "category_name": "Kategória neve",
            "release_date": "2024-01-01",
            "price_huf": 5000,
            "created_at": "2024-01-01 12:00:00",
            "updated_at": "2024-01-01 12:00:00"
        }
    ]
}
```

**Könyv létrehozása**
```
POST /books
Content-Type: application/json

{
    "title": "Könyv címe",
    "author_id": 1,
    "category_id": 1,
    "release_date": "2024-01-01",
    "price_huf": 5000
}
```

**Könyv lekérdezése ID alapján**
```
GET /books/{id}
```

**Válasz példa (EUR árfolyammal):**
```json
{
    "success": true,
    "message": "Book retrieved successfully",
    "data": {
        "id": 1,
        "title": "Könyv címe",
        "author_id": 1,
        "author_name": "Szerző neve",
        "category_id": 1,
        "category_name": "Kategória neve",
        "release_date": "2024-01-01",
        "price_huf": 5000,
        "price_eur": 12.50,
        "created_at": "2024-01-01 12:00:00",
        "updated_at": "2024-01-01 12:00:00"
    }
}
```

**Könyvek keresése**
```
GET /books/search?query=keresési kifejezés
```

A keresés a következő mezőkben történik:
- Könyv címe
- Szerző neve
- Kategória neve

#### Statisztikák

**Drága könyvek (átlag feletti ár)**
```
GET /statistics/expensive-books
```

**Népszerű kategóriák (top 3)**
```
GET /statistics/popular-categories
```

**Válasz példa:**
```json
{
    "success": true,
    "message": "Popular categories retrieved successfully",
    "data": [
        {
            "name": "Fantasy",
            "book_count": 15,
            "avg_price_huf": 4500.50
        }
    ]
}
```

**Top Fantasy & Sci-Fi könyvek (top 3, ár szerint)**
```
GET /statistics/top-fantasy-and-sci-fi
```

### Válasz formátum

Minden API válasz a következő struktúrát követi:

**Sikeres válasz:**
```json
{
    "success": true,
    "message": "Üzenet",
    "data": { ... }
}
```

**Hibás válasz:**
```json
{
    "success": false,
    "message": "Hibaüzenet"
}
```

**HTTP státusz kódok:**
- `200` - Sikeres kérés
- `201` - Sikeres létrehozás
- `404` - Nem található
- `500` - Szerver hiba

## 🏗 Projekt struktúra

```
app/
├── DTO/                           # Data Transfer Objects
│   └── ServiceResponse.php        # Standardizált API válasz objektum
├── Http/
│   ├── Controllers/              # API Controller-ek
│   │   └── BookController.php    # Könyvek kezelése
│   ├── Requests/                 # Form Request validációk
│   │   ├── AddBookRequest.php
│   │   ├── GetBookRequest.php
│   │   └── SearchBooksRequest.php
│   └── Resources/                # API Resource-ok
│       └── BookResource.php      # Könyv adatok formázása
├── Models/                       # Eloquent modellek
│   ├── Author.php
│   ├── Book.php
│   └── Category.php
├── Repositories/                 # Repository pattern implementáció
│   ├── BookRepository.php       # Adatbázis műveletek
│   └── BookRepositoryInterface.php
├── Services/                     # Business logic réteg
│   ├── BookService.php          # Könyvek üzleti logikája
│   └── Exchange/                # Valuta árfolyam service
│       ├── ExchangeRateApiClient.php
│       └── ExchangeRateClientInterface.php
└── Providers/                   # Service Provider-ek
    ├── RepositoryServiceProvider.php
    └── ExchangeRateServiceProvider.php
```

## 🎯 Design Patterns

A projekt több jól bevált design pattern-t használ a karbantarthatóság és a skálázhatóság érdekében:

### 1. Repository Pattern

**Cél**: Az adatbázis műveletek elválasztása a business logikától.

**Implementáció**:
- `BookRepositoryInterface`: Interface az adatbázis műveletekhez
- `BookRepository`: Konkrét implementáció
- `RepositoryServiceProvider`: Dependency injection beállítása

**Előnyök**:
- Könnyű tesztelhetőség (mockolható interface)
- Adatbázis függetlenség
- Központosított adatbázis logika

**Példa használat**:
```php
// Service rétegben
public function __construct(
    private BookRepositoryInterface $repo
) {}
```

### 2. Service Layer Pattern

**Cél**: Business logic központosítása és a Controller-ek leegyszerűsítése.

**Implementáció**:
- `BookService`: Üzleti logika kezelése
- Transaction kezelés
- Cache stratégia
- Error handling

**Előnyök**:
- Vékony Controller-ek
- Újrafelhasználható business logic
- Könnyű tesztelhetőség

**Példa használat**:
```php
// Controller-ben
public function getBooks(BookService $bookService): JsonResponse
{
    $response = $bookService->getBooks();
    return response()->json($response->toArray(), $response->status);
}
```

### 3. DTO (Data Transfer Object) Pattern

**Cél**: Standardizált API válaszok.

**Implementáció**:
- `ServiceResponse`: Egységes válasz struktúra
- `success`, `message`, `data`, `status` mezők

**Előnyök**:
- Konzisztens API válaszok
- Könnyű kliens oldali feldolgozás
- Hibakezelés standardizálása

**Példa használat**:
```php
return new ServiceResponse(
    success: true,
    message: 'Books listed successfully',
    data: $resource,
    status: 200
);
```

### 4. Dependency Injection

**Cél**: Loose coupling és könnyű tesztelhetőség.

**Implementáció**:
- Constructor injection
- Service Provider-ekben történő binding
- Interface alapú injection

**Előnyök**:
- Könnyű mockolás teszteléshez
- Rugalmas implementáció cseréje
- Tiszta függőségek

**Példa használat**:
```php
// Service Provider-ben
$this->app->bind(
    BookRepositoryInterface::class, 
    BookRepository::class
);
```

### 5. Form Request Validation

**Cél**: Bemeneti adatok validálása és autorizáció.

**Implementáció**:
- `AddBookRequest`, `GetBookRequest`, `SearchBooksRequest`
- Validációs szabályok
- Egyedi hibaüzenetek

**Előnyök**:
- Központosított validáció
- Tiszta Controller-ek
- Újrafelhasználható validációs logika

### 6. API Resources

**Cél**: API válaszok formázása és átalakítása.

**Implementáció**:
- `BookResource`: Könyv adatok formázása
- Relációk kezelése (author, category)

**Előnyök**:
- Konzisztens API válaszok
- Adatstruktúra változtatás nélküli API módosítás
- Védett belső adatstruktúra

### 7. Strategy Pattern (Exchange Rate)

**Cél**: Különböző valuta árfolyam API-k használata.

**Implementáció**:
- `ExchangeRateClientInterface`: Interface
- `ExchangeRateApiClient`: Konkrét implementáció
- Könnyen cserélhető implementációk

**Előnyök**:
- Könnyű API provider csere
- Tesztelhetőség (mockolható)
- Bővíthetőség

## ⚡ Optimalizálások

### Query optimalizálások

**getExpensiveBooks()**
- **Előtte**: 2 query (AVG számítás + WHERE lekérdezés)
- **Utána**: 1 query subquery-vel
- **Eredmény**: 50% kevesebb adatbázis hívás

**getTopFantasyAndSciFiBooks()**
- **Előtte**: 2 query (kategória ID-k + könyvek)
- **Utána**: 1 query `whereHas()` használatával
- **Eredmény**: 50% kevesebb adatbázis hívás

### Cache stratégia

**Cache-elt végpontok**:
- `getBookById()`: 2 órás cache (`book:{id}`)
- `getPopularCategories()`: 2 órás cache (`popular_categories`)
- `getTopFantasyAndSciFiBooks()`: 30 perces cache (`top_fantasy_scifi_books`)
- Exchange rate conversion: Cache-elt (külső API hívások csökkentése)

**Cache invalidation**:
- Új könyv hozzáadásakor automatikusan invalidálódnak a releváns cache-ek
- `addBook()` metódusban történik a cache törlés

**Teljesítmény javulás**:
- Cache-elt végpontok: ~90% gyorsabb válaszidő
- Query optimalizálások: ~50% kevesebb adatbázis terhelés

## 🧪 Tesztelés

```bash
php artisan test
```

## 🔧 Fejlesztés

### Code Style
```bash
./vendor/bin/pint
```

### Logok megtekintése
```bash
tail -f storage/logs/laravel.log
```

### Cache törlése
```bash
php artisan cache:clear
```

## 📝 Adatbázis séma

### Books tábla
- `id`: Primary key
- `title`: Könyv címe
- `author_id`: Foreign key (authors)
- `category_id`: Foreign key (categories)
- `release_date`: Megjelenés dátuma
- `price_huf`: Ár forintban
- `created_at`, `updated_at`: Timestamps

### Relációk
- Book → Author (belongsTo)
- Book → Category (belongsTo)

### Indexek
- `unique(['title', 'author_id'])`: Egyedi könyv-szerző kombináció

## 🔐 Biztonság

- Form Request validáció minden bemeneti adathoz
- SQL injection védelem (Eloquent ORM)
- XSS védelem (automatikus escaping)
- Standardizált hibakezelés

## 📄 Licenc

MIT License

## 👥 Közreműködés

A projekt nyitott a közreműködésre. Kérjük, először nyiss egy issue-t a változtatásról, mielőtt pull request-et nyitnál.
