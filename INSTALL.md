POSTUP INSTALACIE

1. Rozbalit zip alebo naklonovat repozitar https://github.com/dominik01/kniznica.git
2. Vojst do rootovskeho adresara projetku a spustit v konzole "composer install"
3. Instalacia sa opyta niekolko krokov, staci stlacat enter dolezite je ale nastavit meno databazy usera a heslo
4. Importovat subor kniznica.sql ALEBO vytvorit prazdnu databazu v mySQL s prislusnym menom a spustit prikaz php bin/console doctrine:schema:update --force co vytvori prazdne tabulky
5. prikazom php bin/console server:run sa sputi aplikacia na localhost:8000 pripadne otvorit localhost/kniznica/web/app_dev.php

