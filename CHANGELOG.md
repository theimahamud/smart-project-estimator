# Changelog

All notable changes to SmartEstimate AI will be documented in this file.

## [1.2.0] - 2025-12-16 - **Rubel Mahamud**

### 🚀 Major Enhancements

- **FIXED**: AI timeout issues - Extended timeouts from 60s to 180s with proper Guzzle configuration
- **ADDED**: Advanced team parameter system with custom hourly rates
- **ADDED**: Complete client management CRUD operations
- **FIXED**: Enum type errors in Blade templates (ucfirst() issues)
- **ENHANCED**: Cost calculation system with team-aware pricing

### 🔧 Technical Improvements

- Enhanced EstimationAiClient with retry logic and timeout handling
- Added proper enum casting and display methods
- Implemented client authorization and user-scoped data access
- Updated database schema with address field migration
- Applied Laravel Pint code formatting standards

### 🎨 UI/UX Improvements

- Professional client management interface
- Enhanced estimation form with team configuration
- Improved navigation with proper active states
- Mobile-responsive design across all pages
- Fixed authentication page routing issues

### 📦 Dependencies & Configuration

- Configured PrismPHP with DeepSeek API integration
- Updated AI service configuration with extended timeouts
- Enhanced database relationships and model casting
- Improved error handling and logging

---

## [1.0.0] - 2025-12-09 - Initial Release

### Core Features

- Basic AI estimation functionality
- PDF upload and text extraction
- User authentication system
- Project and estimate models
- Basic cost calculation

---

**Maintained by**: Rubel Mahamud (rubelmahamud9997@gmail.com)
