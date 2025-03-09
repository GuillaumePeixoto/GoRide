# Démarrer le projet

Ce projet utilise Symfony pour le backend et Vue.js/Nuxt pour le frontend.

## Script d'exécution

### Symfony

```bash
#!/bin/bash
cd /workspace/backend && symfony server:start --port=8000 --no-tls &
```

### VueJS

```bash
#!/bin/bash
cd /workspace/frontend && npm run dev --host
```
