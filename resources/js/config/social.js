export function getSocialLinks() {
  const github = (import.meta.env.VITE_GITHUB_URL || '').trim();
  const linkedin = (import.meta.env.VITE_LINKEDIN_URL || '').trim();

  return {
    github: github.startsWith('http') ? github : null,
    linkedin: linkedin.startsWith('http') ? linkedin : null,
  };
}

