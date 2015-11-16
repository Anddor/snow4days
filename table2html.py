from sys import stdin

for line in stdin:
    cells = line.strip().split(";")
    print("        <tr>")
    print("            <td>", end='')
    print("</td>\n            <td>".join(cells), end='')
    print("</td>\n        </tr>")
