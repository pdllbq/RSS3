# RSS3

> Status: this project is currently under active development. Core functionality is still being built, and some planned features are not yet implemented.

RSS3 is an experimental RSS reader designed to help users follow world events around the topics they care about.

## Overview

The project aims to collect articles from multiple RSS sources, reduce noise, group similar stories, and present them in a cleaner and more useful way. Instead of browsing dozens of feeds manually, users can focus on meaningful updates and discover relevant content more efficiently.

## Project status

This project is still under active development. The core functionality is being built, and some of the planned features are not yet fully implemented.

## Planned features

- Aggregation of RSS and Atom feeds
- Grouping and deduplication of similar articles
- Categorization of content by topic or interest
- AI-powered summaries and highlights
- Algorithmic recommendations for relevant stories
- A cleaner reading experience for tracking important events

## Tech stack

- PHP with Laravel
- Filament for admin and management interfaces
- OpenAI integration through Laravel AI
- Vite for frontend assets
- SimplePie for feed parsing

## Getting started

1. Clone the repository
2. Install PHP and Node.js dependencies:
   ```bash
   composer install
   npm install
   ```
3. Prepare the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```
4. Start the development environment:
   ```bash
   composer run dev
   ```

## Roadmap

- Feed ingestion and storage
- Duplicate detection and article grouping
- AI-based categorization and summarization
- Personalized recommendations and improved discovery

## License

This project is licensed under the MIT License.
