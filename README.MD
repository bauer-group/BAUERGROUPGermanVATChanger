# MwSt. / USt. Anpassung im Rahmen des Corona / COVID-19 Konjunkturprogramms

## Einführung
Das Plugin passt im Rahmen des Corona / COVID-19 Konjunkturprogramms die MwSt. / USt. automatisch am Stichtag '01.07.2020 00:00' an. Die USt wird von 19% auf 16% und von 7% auf 5% gesenkt. Ebenso wird der Steuersatz wieder am Stichtag '01.01.2021 00:00' automatisch auf die alten Werte erhöht.

## Beschreibung

Das Plugin benötigt zwingend aktivierte und funktionierende Cronjobs im Shop für die ordnungsgemäße Funktion. Achten sie darauf, dass die Cronjobs auf jeden Fall zuverlässig funktionieren. Testen sie in jedem Fall die ordnungsgemäße Funktion in einer eigenständigen Testumgebung, bevor das Plugin in ihrem produktiven Shop eingesetzt wird. Fertigen sie in jedem Fall vor Installation und Einsatz des Plugins eine Datensicherung ihres Shops an.

**Funktionsumfang:**

-   Das Plugin pflegt den Steuersatz für die Artikel und legt die neuen Steuersätze an, sofern diese nicht existieren.
-   Es werden den Artikeln die neuen Steuersätze zugewiesen, anhand ihrer bisherigen Umsatzsteuersätze (19 zu 16% und 7 zu 5%.
-   Die Steuersätze für sonstige Leistungen (Gutscheine, Rabatte) werden von 19 auf 16% geändert.

**Welche Bereiche werden nicht automatisch bearbeitet:**  

-   Das Plugin pflegt die Steuersätze für Versandkosten nicht automatisch. Das ist nur relevant, wenn die Auto-Ermittlung bei den Versandkosten nicht aktiviert wurde). Ist diese Aktiv gibt es hier voraussichtlich keine Probleme.
-   Auch pflegt es die Steuersätze für Gutscheine nicht automatisch. Das ist relevant, wenn die Auto-Ermittlung der Steuer bei Gutscheinen nicht genutzt wird.
-   Aktuell werden keine Subshops für die automatische Umstellung der USt. sonstiger Leistungen (Gutscheine, Rabatte) unterstützt.

**Beachtenswertes:**  

-   Das Plugin wurde bisher nur gegen die Shopware Standardumgebung getestet.
-   Textbausteine, in denen evtl. Steuersätze eingepflegt wurden, sind ebenfalls nicht Bestandteil der automatischen Umstellung.

## Installationsanleitung
**Vor der Installation:**
-   Erstellen sie vor der Installation des Plugins in jedem Fall eine Datensicherung ihres Shops!
-   Jeder Shop ist individuell. Testen sie das Plugin vorab in einer Testumgebung, die eine Kopie ihres produktiven Shop darstellt.
-   Das Plugin wurde gegen die Shopware Standardumgebung getestet. Beachten sie das es nicht möglich ist alle Kombinationen von Umgebungen zu testen. Daher tragen sie unbedingt dafür sorge vorab, die korrekte Funktion des Plugins in einer Testumgebung zu validieren. Der Einsatz des Plugins außerhalb der Standardumgebung, erfolgt auf ihr eigenes Risiko. Die Standardumgebung stellt hier die Shopware 5.6.x Community Version, in deutsche Sprache und einem Storefront dar. Wie dies nach der Installation der Community Version der Fall ist. Für alle anderen Umgebungen ist das Plugin nicht getestet und freigegeben.

**Nach der Installation:**
-   Legen sie in den Plugineinstellungen fest ob die Bruttopreise im Shop beibehalten werden sollen. In diesem Fall werden alle Nettopreise aller Kundengruppen neu berechnet.
-   Sie können den Zeitpunkt für die Absenkung und Erhöhung der Umsatzsteuer frei festlegen. Damit kann eine Umstellung in der Testumgebung vorab simuliert werden.

## Haftung:
Für Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit sowie bei Vorsatz und grober Fahrlässigkeit haften wir unbeschränkt. Im Rahmen der unentgeltlichen Überlassung dieses Plugins ist die Haftung bei einfacher Fahrlässigkeit ausgeschlossen. Für den Verlust oder die Veränderung von Daten haften wir nur, wenn ein solcher Verlust bzw. Veränderung durch die vom Kunden unterlassenen Datensicherungsmaßnahmen vermeidbar gewesen wäre.

## Gewährleistung:
Sofern nicht ausdrücklich schriftlich angegeben, gibt der Hersteller dieser Erweiterung keine Zusicherungen oder Gewährleistungen in Bezug auf diese Software ab und schließt ausdrücklich alle anderen ausdrücklichen oder stillschweigenden mündlichen oder schriftlichen Gewährleistungen aus, einschließlich, jedoch nicht beschränkt auf stillschweigende Gewährleistungen von handelsüblicher Qualität oder Eignung für eine bestimmte Zweck.
