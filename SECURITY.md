# Security notice

This is a legacy research/teaching codebase and must not be exposed directly to the Internet without remediation.

已知重要風險包括：舊版 PHP/Python/TensorFlow/CUDA、未完整驗證的檔案路徑、Shell 命令字串拼接、歷史應用中的明文密碼流程，以及可寫入 Web 目錄的執行資料。請先依兩份建置規格中的安全閘門完成修正，再上線。

Known high-risk areas include obsolete PHP/Python/TensorFlow/CUDA versions, insufficient path validation, shell command construction from request data, plaintext-password flows in a historical application, and runtime data stored under the web tree. Complete the security gates in the deployment specifications before exposure.

Never commit real values in place of:

- `ACCOUNT`
- `PASSWORD`
- `PASSWORD_HASH`
- `API_KEY`

Store production secrets outside the document root with owner-only permissions or in a managed secret store. Run Apache and workers as dedicated non-login users with no sudo privileges.

## Audit findings requiring remediation

The migration review identified the following release blockers. They are documented rather than silently changed because each fix affects application behavior and requires regression tests:

- `source/apps/P082/webserver/list_view_exe.php` and `export_exe.php` construct shell commands from request-derived values. Replace shell composition with a fixed executable plus a validated argument array, or remove the shell boundary entirely.
- `source/apps/project_template/screenshot.php` also invokes external commands with insufficient trust-boundary enforcement.
- `source/apps/project_template/upload.php` can delete a request-selected path. Resolve it against a fixed upload root, reject traversal and links, and enforce an allow-list before deletion.
- Historical account stores were directly reachable from the old HTTP document tree. The real files are excluded; the example Apache configuration explicitly denies their names.
- The historical teacher-login implementation uses plaintext password comparison. Migrate stored values and verify them with a supported password-hashing API.
- The old service used HTTP only. TLS, secure cookie flags, CSRF defenses, and upload controls are mandatory before redeployment.
- The former Apache worker identity shared a privileged interactive account. The deployment examples use a dedicated, non-login, non-sudo worker instead.

The source must be treated as non-production legacy code until these items have been fixed and independently tested. Reporting a security issue should not include real credentials, personal data, or private datasets.
