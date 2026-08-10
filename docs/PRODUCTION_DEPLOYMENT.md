# KulaCRM Production Deployment & Zero Data Loss Architecture

Target Domain: **https://kulacrm.com/**

---

## 🛡️ Core Guarantees

1. **Application & Database Lifecycle Separation**:
   Application deployment (container builds, CodeIgniter asset updates) never automatically resets, drops, or truncates production database tables or tenant records.

2. **Idempotent & Non-Destructive Migrations**:
   All database schema changes are version-controlled, executed via non-destructive SQL (`CREATE TABLE IF NOT EXISTS`, `ALTER TABLE ... ADD COLUMN IF NOT EXISTS`), and wrapped in transactional checks.

3. **Pre-flight & Post-flight Data Integrity Gates**:
   Before and after every production deployment, non-sensitive tenant entity counts (`tenants`, `users`, `client`, `supplier`, `sales`, `livestock`, etc.) are captured and compared. Deployments automatically abort if any record loss is detected.

4. **Zero-Downtime Expand-Migrate-Contract Strategy**:
   Breaking schema alterations follow a 6-phase expand-migrate-contract cycle ensuring backward compatibility with live application instances.

---

## 🚀 Automated Deployment Pipeline Flow

```text
1. Git Push / CI Trigger
     ↓
2. Automated Unit & Syntax Tests
     ↓
3. Security & Vulnerability Scan
     ↓
4. Automated Pre-Deployment Database Snapshot
   `php scripts/verify_db_integrity.php --pre`
     ↓
5. Idempotent Database Migrations Applied
   `mysql -u $DB_USER -p$DB_PASS $DB_NAME < DB/ai_migration.sql`
     ↓
6. Application Container Deployment (Coolify / Docker)
     ↓
7. Post-Deployment Integrity Check
   `php scripts/verify_db_integrity.php --post`
     ↓
8. Container Healthcheck & Traffic Routing (Traefik / Nginx)
     ↓
9. Production Complete (Zero Data Loss Verified)
```

---

## 💾 Backup & Disaster Recovery (RPO & RTO)

### Backup Mechanisms
- **Automated Hourly Snapshots**: Managed via database provider / mysqldump cron task.
- **Pre-Deployment Backup Guard**: Automated database export before executing non-trivial migrations.

### Recovery Metrics
- **RPO (Recovery Point Objective)**: < 1 hour.
- **RTO (Recovery Time Objective)**: < 15 minutes.

---

## 🔄 Safe Application Rollback Procedure

To roll back an application release without touching tenant data:
1. Roll back the Docker container image / git commit tag (`git checkout <previous_stable_commit>`).
2. Do **NOT** execute database `DOWN` migrations unless explicitly required and verified against a fresh snapshot.
3. Verify application health using `php scripts/verify_db_integrity.php --post`.
