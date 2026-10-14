# Architecture

The architecture diagram is embedded in the root `README.md` using Mermaid, which GitHub renders directly. No separate architecture image is required.

Intended flow:
1. Browser requests reach Nginx on the public web tier.
2. Requests for PHP pages are reverse-proxied to the private application tier.
3. Nginx on the application tier passes PHP scripts to PHP-FPM.
4. The PHP application connects to RDS for MySQL over TCP 3306.

Make sure this reflects the deployed environment before using it as a final architecture description.
