def bin_search(arr, l, h, num):
	if h >= l:
		mid = (high + low) // 2
		if arr[mid] == x:
			return mid
		elif x < arr[mid]:
			return bin_search(arr, l, mid - 1, num)
		else:
			return bin_search(arr, mid + 1, h, num)
	else:
		return None



from random import randint

def bin_search(arr,l,h,number):
	if h<l:
		return None
	mid = (l+h)//2
	if arr[mid]==number:
		return mid
	elif number<arr[mid]:
		return bin_search(arr,l,mid-1,number)
	else:
		return bin_search(arr,mid+1,h,number)

def main():
	arr = [ 7,5,6,9,7,1,3,-3,-2,0,-40]
	arr.sort()
	print(arr)
	num = int(input("Nhap so can tim: "))
	found = bin_search(arr, 0, len(arr)-1, num)
	print("Not found" if found is None else f"Found at index {found}")

if __name__ == '__main__':
	main()