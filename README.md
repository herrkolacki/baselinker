# BaseLinker Integration Service 🚀

Aplikacja integrująca API BaseLinkera, napisana w **Symfony 6.4** i **PHP 8.3**.
System pobiera zamówienia i przetwarza je przy użyciu **Wzorca Strategia (Strategy Pattern)**, obsługując różne źródła zamówień (Allegro, Amazon, Zamówienia Ręczne) w ujednolicony sposób.

## 📋 Wymagania

Aby uruchomić projekt, potrzebujesz jedynie:
* **Docker** oraz **Docker Compose** (zainstalowane na systemie hosta).
* Token API BaseLinker (do pobierania zamówień).

## 🛠️ Instalacja i Uruchomienie

Postępuj zgodnie z poniższymi krokami, aby postawić środowisko deweloperskie.

### 1. Konfiguracja środowiska
Utwórz plik `.env` w głównym katalogu projektu i uzupełnij go swoim tokenem API oraz konfiguracją Redisa.

**Plik:** `.env`
```dotenv
APP_ENV=dev
APP_SECRET=zmien_na_losowy_ciąg_znakow
# Token z panelu BaseLinker -> Moje Konto -> API
BASELINKER_TOKEN=TWOJ_TOKEN_TUTAJ

# Konfiguracja kolejki (Dla trybu async użyj redis, dla sync zostaw sync)
MESSENGER_TRANSPORT_DSN=redis://redis:6379/messages

# zbudowanie kontererów, to może trochę potrwać
docker-compose up -d --build  

# instalowanie bibliotek PHP (Coposer)
docker-compose exec php composer install

# Uruchamianie
./fetch.sh

# testy
docker-compose exec php php bin/phpunit