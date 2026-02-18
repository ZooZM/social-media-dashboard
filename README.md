# Social Media Automation Dashboard

A Laravel 11 dashboard application integrated with MongoDB for managing social media automation powered by n8n. This system allows you to manage clients, track AI-powered interactions, and provide context data for intelligent responses.

## 🚀 Features

- **Client Management**: CRUD operations for managing clients with profile images and business categories
- **Context Data**: Store structured data (prices, FAQs, brand voice) for AI-powered responses
- **n8n Integration**: RESTful API endpoints for seamless workflow integration
- **Interaction Logs**: Track and view all AI conversations across multiple platforms
- **Dashboard Analytics**: Real-time statistics and platform breakdown
- **Responsive UI**: Modern Tailwind CSS interface with dark-themed sidebar

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MongoDB (local or MongoDB Atlas)

## 🔧 Installation

### 1. Clone the Repository

```bash
cd /Users/mohammedali/Documents/work/Laravel/social-media-dashboard
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the `.env.example` to `.env` (already done during setup):

```bash
cp .env.example .env
```

Update the following MongoDB configuration in `.env`:

```env
MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/
MONGODB_DATABASE=social_media_dashboard
API_TOKEN=your-secure-random-token-here
```

**Important**: Change `API_TOKEN` to a secure random string for production.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

## 🏃 Running the Application

### Development Server

```bash
php artisan serve
```

Visit: http://127.0.0.1:8000

## 📡 n8n Integration

### API Endpoints

All API endpoints require Bearer token authentication.

#### 1. Get Client Data

**Endpoint**: `GET /api/n8n/client/{client_id}`

**Headers**:
```
Authorization: Bearer YOUR_API_TOKEN
```

**Response**:
```json
{
  "success": true,
  "data": {
    "id": "65d1e2f3a4b5c6d7e8f9a0b1",
    "name": "Arab Marketing Agency",
    "business_category": "Real Estate",
    "context_data": {
      "prices": { "basic": "500 SAR", "premium": "1500 SAR" },
      "faqs": ["What payment methods?", "Is installment available?"],
      "brand_voice": "Professional and friendly"
    }
  }
}
```

#### 2. Log Interaction

**Endpoint**: `POST /api/n8n/log`

**Headers**:
```
Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json
```

**Body**:
```json
{
  "client_id": "65d1e2f3a4b5c6d7e8f9a0b1",
  "message": "Hello, I want an apartment",
  "response": "Hello! We have several options available...",
  "platform": "whatsapp",
  "metadata": {
    "user_id": "966501234567",
    "conversation_id": "wa_conv_12345"
  }
}
```

**Response**:
```json
{
  "success": true,
  "message": "Interaction logged successfully",
  "log_id": "65d1e2f3a4b5c6d7e8f9a0b2"
}
```

### Example n8n Workflow

```
1. Webhook (WhatsApp/Instagram message received)
   ↓
2. HTTP Request - GET /api/n8n/client/{id}
   ↓
3. OpenAI/Anthropic Node (with context_data)
   ↓
4. HTTP Request - POST /api/n8n/log
   ↓
5. Send response back to platform
```

## 🗄️ MongoDB Schema

### Clients Collection

```javascript
{
  "_id": ObjectId,
  "name": String,
  "business_category": String,
  "profile_image": String,
  "context_data": {
    "prices": Object,
    "faqs": Array,
    "brand_voice": String
  },
  "created_at": ISODate,
  "updated_at": ISODate
}
```

### Interaction Logs Collection

```javascript
{
  "_id": ObjectId,
  "client_id": ObjectId,
  "message": String,
  "response": String,
  "platform": String,
  "metadata": Object,
  "created_at": ISODate
}
```

## 🎨 Technology Stack

- **Backend**: Laravel 11, MongoDB Laravel Driver (v5.6)
- **Frontend**: Blade Templates, Tailwind CSS v4, Alpine.js
- **Database**: MongoDB
- **Build Tool**: Vite

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/N8nController.php
│   │   ├── ClientController.php
│   │   ├── DashboardController.php
│   │   └── LogController.php
│   └── Middleware/
│       └── ValidateN8nToken.php
├── Models/
│   ├── Client.php
│   └── InteractionLog.php
└── Services/
    ├── ClientService.php
    └── InteractionLogService.php

resources/
├── views/
│   ├── layouts/app.blade.php
│   ├── components/sidebar.blade.php
│   ├── dashboard/index.blade.php
│   ├── clients/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── logs/index.blade.php
├── css/app.css
└── js/app.js
```

## 🔐 Security

- API endpoints are protected with Bearer token authentication
- File uploads are validated (max 2MB, images only)
- Input validation on all forms
- MongoDB injection protection via Eloquent ORM

## 🧪 Testing

### Test MongoDB Connection

```bash
php artisan tinker
```

Then run:
```php
DB::connection('mongodb')->getMongoDB()->command(['ping' => 1]);
// Should return: {"ok": 1}
```

## 📝 License

This project is open-source software.

## 🤝 Support

For issues or questions, please refer to the MongoDB schema documentation included in the project.
