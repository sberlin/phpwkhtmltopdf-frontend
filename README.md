# phpwkhtmltopdf-frontend
Convert HTML Websites to PDF files with Angular 1.4 and PHP

# Description
When traveling through rural area in a train and having no Internet connection, I like to save my browser bookmarks as PDF files beforehand and read them on the go. With this single page app I can automatize PDF generation.
It's a frontend with Angular 1.4 for <a href="https://github.com/mikehaertl/phpwkhtmltopdf">mikehaertl/phpwkhtmltopdf</a>. Bootstrap is used for basic styling and responsive design.

CSS and JS are embedded in the HTML for simplicity. Dependencies are loaded from other domains or CDN.

# References
- <a href="https://github.com/mikehaertl/phpwkhtmltopdf">mikehaertl/phpwkhtmltopdf</a>
- <a href="http://wkhtmltopdf.org/">wkhtmltopdf</a>
- <a href="https://github.com/eligrey/FileSaver.js/">eligrey/FileSaver.js</a>
- <a href="https://mathiasbynens.be/demo/url-regex">URL RegEx</a>

# Install
Copy the source files into the web content directory of your webserver.

Use Composer like mentioned in <a href="https://github.com/mikehaertl/phpwkhtmltopdf">mikehaertl/phpwkhtmltopdf</a> to get the interface for using <a href="http://wkhtmltopdf.org/">wkhtmltopdf</a>. The downloaded library should now reside in a directory called "vendor" relative to the project's source files.

# Use
Copy the links of the websites you want to be converted into the input fields. You can add and remove input fields on the fly. Submit the links and the server will generate a zip file containing the converted websites. <a href="https://github.com/eligrey/FileSaver.js/">FileSaver.js</a> is used for the correct cross-browser download behavior.

The app
- validates non-empty submits and input fields
- ensures <a href="https://mathiasbynens.be/demo/url-regex">correct URL formatting</a> (by Jeffrey Friedl)
- drops URL fragments
- sets the file name by using an escaped \<title\> attribute (through a new request)
- sets a generic file name, if no title is available
- writes an error log file for failed conversions
