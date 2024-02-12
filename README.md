# Project Title

Brief description of your Laravel project.

## Table of Contents

-   [Introduction](#introduction)
-   [Features](#features)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Testing](#testing)
-   [Contributing](#contributing)
-   [License](#license)

## Introduction

Provide a brief introduction to your project. Explain what it does and its purpose.

## Features

List the key features of your project.

## Requirements

Outline the requirements needed to run your project.

-   PHP version
-   Laravel version
-   Database requirements, etc.

## Installation

Provide step-by-step instructions on how to install your project.

```bash
# Clone the repository
git clone https://github.com/your-username/your-repo.git

# Navigate to the project directory
cd your-repo

# Install dependencies
composer install

# Set up the environment file
cp .env.example .env
php artisan key:generate

# Install passport with --uuids and enter yes on additional command to rollback and migrate with uuid automatically
# Install Laravel Passport
php artisan passport:install --uuids
# In order to finish configuring client UUIDs, we need to rebuild the Passport database tables. Would you like to rollback and re-run your last migration? (yes/no) [no]:
yes

# Add the following lines to your `.env` file and fill in the values obtained during Passport installation
PASSPORT_CLIENT_ID=your-client-id
PASSPORT_CLIENT_SECRET=your-client-secret

# Run the Passport keys command
php artisan passport:keys
```
