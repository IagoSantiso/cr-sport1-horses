#!/usr/bin/env bash
# Empaqueta el tema y el plugin en dist/ para subirlos desde
# Escritorio › Apariencia › Temas › Añadir › Subir tema  y  Plugins › Añadir › Subir plugin.
set -euo pipefail
cd "$(dirname "$0")/.."
rm -rf dist && mkdir -p dist
( cd wordpress/wp-content/themes && zip -qr ../../../dist/crsport-tema.zip crsport -x '*.DS_Store' )
( cd wordpress/wp-content/plugins && zip -qr ../../../dist/crsport-core-plugin.zip crsport-core -x '*.DS_Store' )
ls -lh dist
