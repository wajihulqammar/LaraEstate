# 🏠 LaraEstate - Real Estate Marketplace

![Laravel](https://img.shields.io/badge/Laravel-11+-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

A full-featured **Real Estate Marketplace** built with **Laravel 11+** and **Bootstrap 5**, similar to OLX/Zameen. Designed specifically for the **Pakistani market** with local currencies (PKR), cities, and area units.

[![GitHub Stars](https://img.shields.io/github/stars/wajihulqammar/LaraEstate?style=social)](https://github.com/wajihulqammar/LaraEstate/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/wajihulqammar/LaraEstate?style=social)](https://github.com/wajihulqammar/LaraEstate/network/members)

---

## 📸 Screenshots

<img width="1521" height="1397" alt="localhost_8000_login" src="https://github.com/user-attachments/assets/f4524107-878e-4e1d-92d0-eb1b14295a45" />
<img width="1521" height="3335" alt="localhost_8000_" src="https://github.com/user-attachments/assets/c0d00ff5-b2d6-4ed7-ab7e-fda8738f27b4" />
<img width="1521" height="1997" alt="localhost_8000_dashboard" src="https://github.com/user-attachments/assets/fa41a56f-8b4c-40aa-9bfd-80349a952a1f" />
<img width="1521" height="1620" alt="localhost_8000_properties" src="https://github.com/user-attachments/assets/6936590c-8024-4a5b-b37f-6a38230ec582" />
<img width="1521" height="2117" alt="localhost_8000_chats" src="https://github.com/user-attachments/assets/43fec6e0-115f-4181-a853-793f06094c74" />
<img width="1521" height="1749" alt="localhost_8000_inquiries" src="https://github.com/user-attachments/assets/e35f824f-484c-4ec8-a81a-a555686ea885" />
<img width="1521" height="1968" alt="localhost_8000_properties_create" src="https://github.com/user-attachments/assets/3eb795f7-65d4-48db-b03b-0a8898884ac9" />
<img width="1521" height="2697" alt="localhost_8000_admin_dashboard" src="https://github.com/user-attachments/assets/46e1acd8-459c-4cbd-9d79-5787dc48c0a7" />
<img width="1521" height="1556" alt="localhost_8000_admin_properties" src="https://github.com/user-attachments/assets/183c2fb1-de12-4370-9a50-afc05abeac79" />
<img width="1521" height="3182" alt="localhost_8000_admin_users" src="https://github.com/user-attachments/assets/99adef4c-922b-4e74-a7b7-39244003e5e3" />
<img width="1521" height="3809" alt="localhost_8000_admin_inquiries" src="https://github.com/user-attachments/assets/3cb16afd-e4ca-4231-9526-5896281e849d" />
<img width="1521" height="2654" alt="localhost_8000_admin_chats" src="https://github.com/user-attachments/assets/44485ec2-cdb0-4fb9-bdd5-7307787cefb9" />


---

## ✨ Features

### 🔐 **Multi-Role Authentication**
- **Guest Users**: Browse properties, view details
- **Registered Users**: Post properties, send inquiries, chat with sellers
- **Admin Panel**: Separate admin login with full management capabilities

### 🏘️ **Property Management**
- ✅ Multiple property types (House, Apartment, Commercial, Plot, Office)
- ✅ Multi-image upload with featured image support
- ✅ Pakistani area units (Marla, Kanal, Square Feet, Square Yard)
- ✅ PKR currency formatting (₨)
- ✅ Advanced search with filters (city, type, price range)
- ✅ Property approval system (Pending/Approved/Rejected)
- ✅ Featured properties showcase

### 💬 **Communication System**
- ✅ Inquiry system for initial contact
- ✅ Real-time chat between buyers and sellers
- ✅ Message read/unread tracking
- ✅ Auto-refresh chat messages
- ✅ Chat history and management

### 🛡️ **Admin Panel Features**
- ✅ Property approval/rejection workflow
- ✅ User management (ban/unban users)
- ✅ Chat monitoring and moderation
- ✅ Inquiry management
- ✅ Statistics dashboard
- ✅ Featured property management

### 🇵🇰 **Pakistani Localization**
- ✅ Major Pakistani cities dropdown
- ✅ PKR currency with proper formatting
- ✅ Local area measurement units
- ✅ Mobile-friendly responsive design

---

## 🚀 Quick Start

### Prerequisites
```bash
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ / MariaDB
- Node.js & NPM (optional)
```

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/wajihulqammar/LaraEstate.git
cd LaraEstate
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraestate
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

6. **Create storage link**
```bash
php artisan storage:link
```

7. **Start development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔑 Default Login Credentials

### Admin Panel
- **URL**: `http://localhost:8000/admin/login`
- **Email**: `admin@laraestate.com`
- **Password**: `admin123`

### Demo User Account
- **URL**: `http://localhost:8000/login`
- **Email**: `ahmed@example.com`
- **Password**: `password123`

> **Note**: Change these credentials in production!

---

## 📂 Project Structure

```
laraestate/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── HomeController.php
│   │   │   ├── PropertyController.php
│   │   │   ├── ChatController.php
│   │   │   └── InquiryController.php
│   │   ├── Middleware/         # Custom middleware
│   │   └── Policies/           # Authorization policies
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   └── views/
│       ├── layouts/            # Layout templates
│       ├── auth/               # Authentication views
│       ├── properties/         # Property views
│       ├── chats/              # Chat views
│       └── admin/              # Admin panel views
├── routes/
│   └── web.php                 # Application routes
└── public/
    └── storage/                # Symlinked storage
```

---

## 🎨 Technology Stack

| Technology | Purpose |
|------------|---------|
| **Laravel 11+** | Backend Framework |
| **PHP 8.1+** | Server-side Language |
| **MySQL** | Database |
| **Bootstrap 5** | Frontend Framework |
| **Bootstrap Icons** | Icon Library |
| **Blade Templates** | Template Engine |
| **Laravel Policies** | Authorization |

---

## 📋 Database Schema

### Main Tables
- **users** - User accounts (buyers, sellers, admins)
- **properties** - Property listings
- **property_images** - Multiple images per property
- **inquiries** - Initial contact/inquiries
- **chats** - Chat conversations
- **messages** - Individual chat messages

### Relationships
```
User (1) -----> (M) Properties
Property (1) -> (M) PropertyImages
Property (1) -> (M) Inquiries
Chat (1) -----> (M) Messages
User (M) <----> (M) User (via Chat)
```

---

## 🔧 Configuration

### Pakistani Cities
Modify cities list in controllers:
```php
// app/Http/Controllers/PropertyController.php
private function getCities() {
    return [
        'Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 
        'Faisalabad', 'Multan', 'Gujranwala', 'Peshawar',
        'Quetta', 'Sialkot', 'Hyderabad', 'Sargodha'
    ];
}
```

### Currency Formatting
PKR formatting in Property model:
```php
public function getFormattedPriceAttribute() {
    return '₨ ' . number_format($this->price, 0);
}
```

---

## 📱 Key Features Breakdown

### For Buyers
- Browse and search properties
- Contact sellers via inquiry forms
- Real-time chat with sellers
- Save favorite properties
- Track inquiry history

### For Sellers
- Post unlimited properties
- Manage property listings
- Receive and respond to inquiries
- Chat with potential buyers
- Dashboard with statistics

### For Admins
- Approve/reject property listings
- Manage users (activate/ban)
- Monitor chat conversations
- View site statistics
- Moderate reported content

---

## 🛠️ Development Commands

```bash
# Clear all caches
php artisan optimize:clear

# Generate new controller
php artisan make:controller YourController

# Generate new model with migration
php artisan make:model YourModel -m

# Run specific seeder
php artisan db:seed --class=YourSeeder

# Fresh migration with seed
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link
```

---

## 🚀 Deployment

### Production Optimization
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Wajih Ul Qammar**

- GitHub: [@wajihulqammar](https://github.com/wajihulqammar)
- LinkedIn: [Wajih Ul Qammar](https://www.linkedin.com/in/wajih-ul-qammar/)

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap Team
- Pakistani Real Estate Market Inspiration (OLX, Zameen)
- Open Source Community

---

## 📧 Support

If you have any questions or need help with setup, please:
- Open an issue on GitHub
- Contact via LinkedIn
- Email: wajiul.qammar@gmail.com

---

## ⭐ Show Your Support

If you found this project helpful, please give it a ⭐ on GitHub!

[![GitHub Stars](https://img.shields.io/github/stars/wajihulqammar/LaraEstate?style=social)](https://github.com/wajihulqammar/LaraEstate/stargazers)

---

## 🔮 Future Enhancements

- [ ] Email notifications
- [ ] SMS integration for Pakistan
- [ ] Property comparison feature
- [ ] Advanced analytics dashboard
- [ ] Mobile app (Flutter/React Native)
- [ ] Payment gateway integration
- [ ] Map integration
- [ ] Property verification system
- [ ] Review and rating system
- [ ] Multi-language support

---

## 📊 Project Stats

![GitHub repo size](https://img.shields.io/github/repo-size/wajihulqammar/LaraEstate)
![GitHub language count](https://img.shields.io/github/languages/count/wajihulqammar/LaraEstate)
![GitHub top language](https://img.shields.io/github/languages/top/wajihulqammar/LaraEstate)
![GitHub last commit](https://img.shields.io/github/last-commit/wajihulqammar/LaraEstate)

---

**Made with ❤️ for the Pakistani Real Estate Market**
