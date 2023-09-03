/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_BACKEND_API: string
  readonly VITE_AUTH_URL: string
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
