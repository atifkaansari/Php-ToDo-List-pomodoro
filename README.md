# Todo & Pomodoro Uygulaması

Modern, minimal tasarıma sahip Todo List ve Pomodoro Timer entegrasyonlu produktivite uygulaması.

## Özellikler

✨ **Modern Minimal Tasarım**
- Temiz ve minimal kullanıcı arayüzü
- Sistem fontları kullanımı
- Subtle gölgeler ve clean borders
- Responsive tasarım (mobil ve desktop uyumlu)
- Az renk kullanımı ile odaklanmayı artıran tasarım

🚀 **Todo List İşlevselliği**
- Görev ekleme, düzenleme ve silme
- Görev tamamlama/iptal etme
- Öncelik seviyeleri (Düşük, Orta, Yüksek)
- Tarih ve saat bazlı planlama
- Gerçek zamanlı geri sayım timer'ı
- Modal ile kolay görev düzenleme

🍅 **Pomodoro Timer Entegrasyonu**
- 25 dakikalık çalışma seansları
- Otomatik ilerleme takibi
- SVG tabanlı görsel timer
- Günlük istatistik takibi
- Tamamlanan pomodoro sayısı
- Toplam odaklanma süresi

📱 **Responsive Tasarım**
- Mobilde tek sütun düzeni
- Desktop'ta iki sütun düzeni
- Touch-friendly butonlar
- Uyarlanabilir timer boyutu
- Mobil-first yaklaşım

⚡ **Performans ve Kullanılabilirlik**
- Hızlı yükleme süreleri
- Intuitive kullanıcı arayüzü
- Keyboard accessibility
- Clean ve anlaşılır kod yapısı

## Kurulum

### 1. Gereksinimler
- PHP 7.4 veya üzeri
- MySQL 5.7 veya üzeri
- Web sunucu (Apache/Nginx)

### 2. Projeyi İndirin
```bash
git clone https://github.com/atifkaansari/Php-ToDo-List-pomodoro.git
cd Php-ToDo-List-pomodoro
```

### 3. Veritabanı Kurulumu
```sql
-- MySQL'de database.sql dosyasını çalıştırın
mysql -u root -p < database.sql
```

### 4. Veritabanı Bağlantısı
`db.php` dosyasındaki veritabanı bilgilerini düzenleyin:
```php
$host = 'localhost';
$db   = 'todo_list';
$user = 'root';
$pass = 'your_password';
```

### 5. Çalıştırma
- XAMPP, WAMP, MAMP veya yerel PHP sunucusu kullanın
- `http://localhost/php-todolist` adresinden erişim sağlayın

## Dosya Yapısı

```
php-todolist/
├── index.php      # Ana sayfa - Todo & Pomodoro arayüzü
├── app.css        # Modern minimal CSS stilleri
├── db.php         # Veritabanı bağlantı konfigürasyonu
├── add.php        # Yeni görev ekleme işlemi
├── edit.php       # Görev düzenleme işlemi
├── complete.php   # Görev tamamlama işlemi
├── delete.php     # Görev silme işlemi
├── database.sql   # Veritabanı kurulum scripti
└── README.md      # Proje dokümantasyonu
```

## Teknolojiler

- **Backend**: PHP 7.4+, MySQL 5.7+
- **Frontend**: HTML5, CSS3 (Grid/Flexbox), Vanilla JavaScript
- **Tasarım**: CSS Custom Properties, SVG Graphics
- **İkonlar**: Font Awesome 6.0
- **Özellikler**: Responsive Design, CSS Animations, LocalStorage

## Kullanım

### Todo List
1. **Görev Ekleme**: Form alanlarını doldurun ve "Ekle" butonuna tıklayın
2. **Görev Düzenleme**: Görev yanındaki edit ikonuna tıklayın
3. **Görev Tamamlama**: Checkbox'a tıklayarak görevi tamamlayın
4. **Görev Silme**: Silme ikonuna tıklayın

### Pomodoro Timer
1. **Timer Başlatma**: "Başla" butonuna tıklayın
2. **Timer Duraklama**: "Duraklat" butonuna tıklayın
3. **Timer Sıfırlama**: "Sıfırla" butonuna tıklayın
4. **İstatistik Takibi**: Günlük pomodoro ve odaklanma sürenizi görün

## Özelleştirme

### Renk Teması
`app.css` dosyasındaki CSS değişkenlerini düzenleyerek renk temasını değiştirebilirsiniz:

```css
:root {
    --primary: #111827;
    --secondary: #6b7280;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
}
```

### Timer Süreleri
JavaScript kısmında pomodoro sürelerini değiştirebilirsiniz:

```javascript
let timer = {
    timeLeft: 25 * 60, // 25 dakika
    totalTime: 25 * 60
};
```

## Özellikler Detayı

### ✅ Todo List
- Minimal ve temiz arayüz
- Öncelik bazlı renk kodlaması
- Gerçek zamanlı geri sayım
- Responsive tasarım
- Kolay görev yönetimi

### 🍅 Pomodoro Timer
- 25 dakikalık çalışma seansları
- SVG tabanlı görsel ilerleme
- Günlük istatistik takibi
- Otomatik localStorage kaydı
- Responsive timer çemberi

### 📱 Responsive Design
- Mobil: Tek sütun düzeni
- Desktop: İki sütun düzeni
- Uyarlanabilir boyutlar
- Touch-friendly kontroller

## Ekran Görüntüleri

- **Desktop Görünümü**: İki sütunlu layout - sol tarafta todo list, sağ tarafta pomodoro timer
- **Mobil Görünümü**: Tek sütunlu layout - üstte timer, altta todo list
- **Minimal Tasarım**: Temiz, odaklanmayı artıran arayüz

## Katkıda Bulunma

1. Bu projeyi fork edin
2. Yeni bir özellik branch'i oluşturun (`git checkout -b yeni-ozellik`)
3. Değişikliklerinizi commit edin (`git commit -am 'Yeni özellik eklendi'`)
4. Branch'inizi push edin (`git push origin yeni-ozellik`)
5. Pull Request oluşturun

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## İletişim

Herhangi bir sorunuz veya öneriniz için GitHub Issues kullanabilirsiniz.

---

**Todo & Pomodoro** - Minimal tasarım ve güçlü işlevsellik ile produktivitinizi artırın.