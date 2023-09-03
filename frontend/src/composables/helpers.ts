export const useHelper = () => {
  const formatNumber = (num: number) => {
    return parseFloat(num.toFixed(2))
  }

  const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0, // If it's a whole number, don't show decimals
      maximumFractionDigits: 2 // Maximum decimals to show
    }).format(value)
  }

  return { formatNumber, formatCurrency }
}
