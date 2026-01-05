# Book Management Feature

## Overview
The Book Management feature allows you to track and showcase books written by your pastoral team members. This feature integrates seamlessly with the Team Members system and provides comprehensive book management capabilities.

## Features

### Book Information
- **Basic Details**: Title, subtitle, ISBN/ISBN-13
- **Publishing Information**: Publisher, publication date, edition, language, page count
- **Formats**: Hardcover, Paperback, E-book, Audiobook
- **Media**: Cover images, back cover images, sample pages
- **Pricing**: Price with currency support (GBP, USD, EUR)
- **Links**: Purchase links, Amazon links, preview links

### Categorization
- **Categories**: Theology, Biography, Devotional, Biblical Studies, Christian Living, Prayer, Worship, Leadership, Evangelism, Family & Relationships, Youth Ministry, Church History
- **Tags**: Flexible tagging system for easy organization
- **Topics**: Track specific topics covered in each book

### Author Management
- Books are linked to Team Members (pastors)
- View all books by a specific author
- Authors can have multiple books

### Display Features
- Featured books for prominent display
- Custom sort ordering
- Active/inactive status for publishing control
- View count tracking
- Rating system (ready for future reviews)

### SEO Optimization
- Custom slugs for SEO-friendly URLs
- Meta titles, descriptions, and keywords
- Automatic slug generation from title

## Database Structure

### Books Table
The `books` table includes the following key fields:
- `team_member_id` - Foreign key to team_members table
- Book details (title, subtitle, ISBN, description)
- Publishing information (publisher, date, edition, format, pages)
- Media fields (cover_image, back_cover_image, sample_pages)
- Pricing (price, currency)
- Links (purchase_link, amazon_link, preview_link)
- Categories and tags
- Display settings (is_active, is_featured, sort_order)
- SEO fields (slug, meta_title, meta_description, meta_keywords)
- Stats (views_count, rating, reviews_count)

## Relationships

### Book Model Relationships
```php
// Get the author of a book
$book->author;
$book->teamMember;
```

### TeamMember Model Relationships
```php
// Get all books by a team member
$pastor->books;

// Get only published/active books
$pastor->publishedBooks;
```

## Permissions

The following permissions control access to book management:

- `books.view_all` - View all books and publications
- `books.create` - Add new books to the system
- `books.edit` - Modify book information
- `books.delete` - Remove books from the system
- `books.manage_all` - Full control over book management
- `books.*` - All book permissions (wildcard)

### Default Role Permissions
- **Super Admin**: Full access to all book features
- **Pastor**: Full access to manage books (`books.*`)
- **Admin**: Full access to all book features

## Usage

### Adding a New Book

1. Navigate to **Team Management > Books** in the Filament admin panel
2. Click **Create** button
3. Fill in the book details:
   - Select the **Author** (Team Member)
   - Enter **Title** and optional **Subtitle**
   - Add **Description** (supports rich text formatting)
   - Fill in **Publishing Details** (publisher, date, ISBN, etc.)
   - Upload **Cover Image** and optional **Back Cover** image
   - Add **Pricing** information and purchase links
   - Select **Category** and add relevant **Tags** and **Topics**
   - Configure **Display Settings** (Active, Featured, Sort Order)
   - Review **SEO settings** (auto-generated slug, meta information)
4. Click **Create** to save

### Managing Books

#### Filtering Books
Use the table filters to find books by:
- Author
- Category
- Format
- Featured status
- Active status

#### Bulk Actions
- Delete multiple books at once using bulk actions

#### Editing Books
- Click the edit icon on any book to modify its details
- All changes are immediately reflected in the system

### Featured Books
Books marked as "Featured" can be displayed prominently on your website. Use the Featured toggle when creating or editing a book.

### Sort Order
Control the display order of books by setting the Sort Order field. Lower numbers appear first.

## File Storage

Book media files are stored using Laravel's S3 storage:
- Cover images: `books/covers/`
- Back cover images: `books/covers/`
- Sample pages: `books/samples/`

## API Integration (Future Enhancement)

The Book model includes the following helper methods for future frontend integration:

```php
// Get formatted price with currency symbol
$book->formatted_price; // "£14.99"

// Get author name
$book->author_name; // "Pastor Jim Master"

// Get publication year
$book->published_year; // "2022"

// Increment view count
$book->incrementViews();

// Update rating
$book->updateRating(4.5, 10);
```

## Sample Data

The system includes a BookSeeder that creates sample books for demonstration:
- Walking in Faith (Devotional)
- Leadership in the Church (Leadership)
- The Power of Prayer (Prayer)
- Discipleship Foundations (Christian Living)

Run the seeder:
```bash
php artisan db:seed --class=BookSeeder
```

## Migration

To set up the books table, run:
```bash
php artisan migrate
```

## Future Enhancements

Potential features for future development:
1. **Book Reviews**: Allow members to review and rate books
2. **Reading Lists**: Create curated reading lists
3. **Book Recommendations**: Suggest books based on member interests
4. **Integration with E-commerce**: Sell books directly through the website
5. **Book Club Features**: Organize book clubs and discussions
6. **Reading Progress**: Track member reading progress
7. **Book Series**: Group related books into series
8. **Multi-language Support**: Support for books in multiple languages
9. **Digital Downloads**: Allow downloading e-books for members
10. **Book Notifications**: Notify members when new books are published

## Notes

- The slug is auto-generated from the book title but can be customized
- ISBN and ISBN-13 fields must be unique
- Sample pages support up to 10 images
- All images are automatically optimized and can be edited using the built-in image editor
- The system supports rich text formatting in the description field
