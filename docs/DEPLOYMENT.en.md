# P082 Web Platform Deployment, Migration, and Decommissioning Specification

Snapshot date: 2026-09-06

## 1. Purpose and scope

This document explains how to preserve, rebuild, validate, and redeploy the migrated P082 platform. The repository contains the `/var/www/html` entry points, the P082 PHP front end, Python image-processing workers, the historical `project_template` application, the `petang_20230110` parser, selected GDAL helper source, and a sanitized Apache configuration snapshot.

Model weights, user uploads, account databases, generated results, large map tiles, and large research datasets are not included. They must not be committed to GitHub. If required for an authorized restoration, distribute them separately through encrypted private object storage or a model registry with access logging.

## 2. Architecture and data flow

1. Apache serves `/var/www/html`. The original `P082`, `project_template`, and `petang_20230110` entries were symbolic links into `/home/ACCOUNT/...`.
2. The PHP front end handles login, uploads, and job requests and writes queue state into directories such as `img_view_cut_computing` and `lab_computing`.
3. `source/apps/P082/run_cron.sh` runs image segmentation and parasite-egg detection workers every ten seconds.
4. The Python workers use SSD, U-Net, and Faster R-CNN code plus externally supplied weights, then write results under `webserver/user` and result/error directories.
5. `project_template` and `petang_20230110` are historical applications. Assess and deploy them separately; leave them offline if unnecessary.

## 3. Repository contents

```text
source/webroot/                  original /var/www/html entry files
source/apps/P082/webserver/      P082 PHP and static front end
source/apps/P082/computing_node/ Python workers
source/apps/project_template/    historical teaching/map application
source/apps/petang_20230110/     historical parser source
source/tools/gdal2tiles_test/    small source files; bulk data excluded
source/config/apache2/           sanitized original Apache snapshot
environment/                     captured host and package inventories
deploy/                          safer example configuration
```

See `environment/source-map.txt` for path mappings and `environment/excluded-assets.tsv` for every recorded exclusion. Major exclusions include 8.5 GB of `Pre_train_data`, 6.6 GB of model checkpoints, 14 GB of `ITM001` map data, 18 GB of `gdal2tiles_test` data, and about 4.4 GB of petang research/output data.

## 4. Captured source environment

| Component | Captured version or condition |
|---|---|
| Operating system | Ubuntu 20.04.1 LTS; Linux 5.15.0-139-generic; x86_64 |
| CPU / memory | Intel Core i5-7600, four cores; 61 GiB RAM; 2 GiB swap |
| Apache | 2.4.41 (Ubuntu), prefork MPM, `libapache2-mod-php7.0`, HTTP port 80 only |
| PHP | 7.0.33 from Sury packages; Zend OPcache |
| PHP extensions | calendar, ctype, dom, exif, fileinfo, ftp, gd, gettext, json, openssl, PDO, sockets, XML, XSL, zip, and others in `environment/php.txt` |
| System Python | 3.8.10; no system pip detected |
| Conda base | Python 3.8.3; conda 4.8.4 |
| `gdal-env` | Python 3.6.13; GDAL 3.3.1; NumPy 1.19.5; Pillow 5.2.0 |
| `tf-1.0.0-gpu` | Python 3.5.6; tensorflow-gpu 1.0.0; Keras 2.2.2; NumPy 1.14.5; SciPy 0.19.1; OpenCV 3.4.2.17; scikit-image 0.13; scikit-learn 0.19 |
| CUDA | 8.0.61; `/usr/local/cuda -> /usr/local/cuda-8.0` |
| cuDNN | 5.1.10; `libcudnn.so.5.1.10` |
| NVIDIA driver | Package version 450.119.03; `nvidia-smi` could not communicate with the driver, so the GPU model was not captured |

`conda-*.yml` files are environment exports, `conda-*-explicit.txt` files contain exact package URLs, `pip-*-freeze.txt` files contain pip inventories, and `relevant-debian-packages.tsv` records relevant OS packages. Old artifacts may no longer exist on public mirrors. The explicit lists are forensic compatibility evidence, not a security recommendation.

## 5. Security and licensing gates

Complete all of the following before publication or deployment:

1. Revoke and rotate the old SSH/sudo password, API key, teacher credentials, and user credentials. No real value may remain in Git history.
2. Fix the identified shell command injection, arbitrary file deletion, and path-validation flaws. String filtering is not an adequate command-injection fix.
3. Run Apache/PHP and the Python worker under separate, least-privileged accounts. Neither account may have sudo access.
4. Move account stores outside DocumentRoot. Until refactored, deny direct access with Apache `<FilesMatch>` and use mode `0640`.
5. Replace the historical plaintext teacher-password flow in `project_template` with `password_hash()` and `password_verify()`. Never use `teacher.example.json` as a production database.
6. Require HTTPS, Secure/HttpOnly/SameSite cookies, CSRF protection, strict upload MIME/extension validation, normalized paths, size limits, and malware scanning.
7. Confirm ownership and compatible licenses for first- and third-party code. Keep the GitHub repository private until that review is complete; see `LICENSE-NOTICE.md`.
8. Scan the working tree and full Git history with a secret scanner such as Gitleaks. If a secret was committed, rewrite history and rotate it again.

## 6. Recommended host and account design

For legacy GPU compatibility, run this stack on an isolated VM or internal compute node, not directly on the public Internet. Prefer separate front-end and GPU-worker containers or VMs. For a single-host compatibility deployment:

```bash
sudo addgroup --system p082
sudo adduser --system --home /srv/p082 --ingroup p082 --shell /usr/sbin/nologin p082-worker
sudo usermod -aG p082 www-data
sudo install -d -o root -g p082 -m 0750 /srv/p082
sudo install -d -o www-data -g p082 -m 2770 /srv/p082/runtime
```

Do not create a shared interactive service account with sudo. Supply production secrets through a root-only file, systemd credentials, or a secret manager. `ACCOUNT`, `PASSWORD`, `PASSWORD_HASH`, and `API_KEY` are placeholders, never defaults.

## 7. Apache and PHP

### 7.1 Compatibility environment

PHP 7.0 is end-of-life, and Ubuntu 20.04 is not an appropriate new public-facing baseline. For forensic compatibility only, create a non-public Ubuntu 20.04 VM and install Apache 2.4, PHP 7.0, and the required `gd/xml/xsl/zip` extensions from a signed, controlled archive. Do not deploy obsolete PHP directly to the Internet.

```bash
sudo apt update
sudo apt install apache2 libapache2-mod-security2
# Install PHP 7.0 packages matching environment/php.txt from a controlled archive.
sudo a2enmod headers rewrite ssl security2
apache2ctl -M
php -v
php -m
```

The maintainable path is to test and repair PHP 8.x incompatibilities, remove dynamic shell execution, and add automated tests before moving to a supported PHP release. This is not a drop-in upgrade.

### 7.2 Deploy files

```bash
sudo rsync -a --delete source/apps/P082/ /srv/p082/P082/
sudo rsync -a source/apps/project_template/ /srv/p082/project_template/
sudo rsync -a source/apps/petang_20230110/ /srv/p082/petang_20230110/
sudo install -m 0644 source/webroot/index.php /var/www/html/index.php
sudo ln -s /srv/p082/P082/webserver /var/www/html/P082
sudo ln -s /srv/p082/project_template /var/www/html/project_template
sudo ln -s /srv/p082/petang_20230110 /var/www/html/petang_20230110
sudo chown -R root:p082 /srv/p082
sudo find /srv/p082 -type d -exec chmod 0750 {} +
sudo find /srv/p082 -type f -exec chmod 0640 {} +
```

Create only the runtime directories that Apache and the worker need and set them individually to `2770`. Never apply `777` to the source tree. Copy `deploy/apache/p082.conf.example` to `/etc/apache2/sites-available/p082.conf`, then adjust ServerName, paths, and TLS settings:

```bash
sudo a2ensite p082
sudo apache2ctl configtest
sudo systemctl reload apache2
```

Redirect HTTP to HTTPS and add HSTS, an appropriate Content Security Policy, and explicit upload limits before exposure.

## 8. Account-store provisioning

The P082 example format is in `source/apps/P082/webserver/user/log_in_info.example.txt`. Move the production store outside DocumentRoot and configure the application to read that safe path. If temporary compatibility requires the old format, generate bcrypt hashes as follows:

```bash
php -r 'echo password_hash("CHANGE_ME", PASSWORD_BCRYPT), PHP_EOL;'
sudo install -o root -g www-data -m 0640 /dev/null /etc/p082/log_in_info.txt
```

Write `ACCOUNT:PASSWORD_HASH` to the protected file. Never commit real passwords or hashes. Prefer a proper database, per-password hashing, password reset, and audit logging. `teacher.example.json` and `teacheraccount.example.txt` only document formats and are not production credentials.

## 9. Python, CUDA, and model environment

### 9.1 Conda environments

Install Miniconda or Miniforge from a trusted source, then attempt the captured environments:

```bash
conda env create -n tf-1.0.0-gpu -f environment/conda-tf-1.0.0-gpu.yml
conda env create -n gdal-env -f environment/conda-gdal-env.yml
conda list -n tf-1.0.0-gpu
conda list -n gdal-env
```

If old channel artifacts are unavailable, construct an internal package mirror from authorized artifacts and the `*-explicit.txt` evidence. Do not download binaries from unknown mirrors. Python 3.5 and TensorFlow 1.0 are both unsupported; freeze this compatibility worker in an internal image and plan a tested migration.

### 9.2 CUDA 8 and cuDNN 5.1

Obtain CUDA and cuDNN only from NVIDIA or legally retained organizational media under the applicable licenses. Verify the installation:

```bash
readlink -f /usr/local/cuda
/usr/local/cuda/bin/nvcc --version
ldconfig -p | grep cudnn
nvidia-smi
```

Do not start the worker until `nvidia-smi` succeeds and the driver/CUDA combination is compatible. It failed on the source host, so the captured environment was not operational. If required, set service-specific `PATH=/usr/local/cuda/bin:...` and `LD_LIBRARY_PATH=/usr/local/cuda/lib64`; do not overwrite unrelated global settings.

### 9.3 Native extensions and model files

Build the Faster R-CNN Cython extension inside the matching environment:

```bash
cd /srv/p082/P082/computing_node/img_parasite_egg_detection/multi_fast_rcnn/lib/utils
conda run -n tf-1.0.0-gpu python setup.py build_ext --inplace
```

Weights and datasets are not in this repository. After authorized retrieval, place files named by `kind_link_model.json` under the following paths and record their SHA-256 values:

- `.../ssd_fordel/checkpoints/`
- `.../u_net/checkpoints/`
- `.../multi_fast_rcnn/checkpoints/version1/`
- `.../multi_fast_rcnn/checkpoints/version2/`
- `source/apps/P082/webserver/Pre_train_data/`, if required

Do not fetch unknown model files. Do not use public Git LFS for personal data or user-generated content.

## 10. Worker service

Replace hard-coded `/home/ACCOUNT` paths with `/srv/p082` or configuration variables and point `run_cron.sh` to the installed Conda environment. After reviewing `ReadWritePaths`, install the supplied example:

```bash
sudo install -m 0644 deploy/systemd/p082-workers.service.example /etc/systemd/system/p082-workers.service
sudo systemctl daemon-reload
sudo systemctl enable --now p082-workers
sudo systemctl status p082-workers
sudo journalctl -u p082-workers -n 100 --no-pager
```

The service account must not have sudo and must not be able to read SSH keys, shell history, or system password files.

## 11. Acceptance tests

Run at minimum:

```bash
apache2ctl configtest
find /srv/p082 -name '*.php' -print0 | xargs -0 -n1 php -l
curl -fsS http://127.0.0.1/P082/ >/dev/null
curl -I http://127.0.0.1/P082/user/log_in_info.txt
curl -I http://127.0.0.1/project_template/login/teacher.json
```

The protected-file requests must return 403 or 404. Also test failed and successful login, upload constraints, queue creation, worker pickup, known test images for every model, output integrity, concurrency, disk exhaustion, worker restart, backup restoration, and permission boundaries. Confirm that TensorFlow loads each model on the GPU rather than silently falling back to CPU. Use only de-identified fixtures.

## 12. Backup, restoration, and shutdown

A recoverable backup includes the Git commit, deployment configuration, authorized private-model inventory and SHA-256 values, secret-manager configuration, encrypted account/database backups, and required runtime data. Perform real restoration drills.

Restore in this order: provision a clean host; install pinned versions; check out a specific commit; restore private configuration, models, and data; establish permissions; run syntax and security tests; smoke-test on localhost; then enable TLS traffic.

Only after the system owner separately approves formal decommissioning, run:

```bash
sudo systemctl stop apache2
sudo systemctl disable apache2
systemctl is-active apache2
systemctl is-enabled apache2
ss -lntp | grep ':80 '
```

After an approved decommission, expected results are `inactive`, `disabled`, and no listener on port 80. Do not run these commands without explicit approval. This preparation task leaves the source service operational. Service shutdown is not data erasure: restrict network access, rotate old credentials, and retain or erase the source host only under the applicable data-retention policy.

## 13. Character encoding

Some historical `project_template` files appear to use Big5 or already contain mojibake. Preserve original bytes first. Detect the encoding, create a UTF-8 copy with `iconv`, and compare rendered pages before replacing anything. A blind UTF-8 rewrite can cause irreversible loss.

## 14. Modernization path

In the short term, isolate the legacy GPU worker and keep the web front end off the public network. Next, remove shell execution, introduce a real job queue and database, and add unit and integration tests. Finally, migrate to supported PHP, Python, TensorFlow, and CUDA versions and revalidate model output. Use fixed reference images and compare numeric/detection results; successful process startup alone is not sufficient validation.
